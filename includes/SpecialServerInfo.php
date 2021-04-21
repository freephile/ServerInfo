<?php

class SpecialServerInfo extends SpecialPage {

	public $mMode;

	public function __construct( $name = 'ServerInfo' ) {
		parent::__construct(
			$name,
			"viewserverinfo",  // rights required to view
			true // show in Special:SpecialPages
		);
	}

	function execute( $par ) {

		// Only allow users with 'viewserverinfo' right (sysop by default) to access
		$user = $this->getUser();
		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
			return;
		}

		$webRequest = $this->getRequest();
		// Default to phpinfo
		$requestedMode = $webRequest->getVal( 'mode', 'phpinfo' );

		$modes = [ 'httpdstatus', 'httpdinfo', 'phpinfo' ];

		$headerLinks = [];
		foreach ( $modes as $mode ) {
			$linkText = $this->msg( 'serverinfo-mode-' . $mode )->text();

			if ( $mode === $requestedMode ) {
				$headerLinks[] = Xml::element( 'strong', null, $linkText );
			}
			else {
				$headerLinks[] = Linker::link(
					$this->getPageTitle(),
					$linkText,
					[], // custom attributes
					[ 'mode' => $mode ]
				);
			}
		}

		$header = '<span class="successbox">';
		$header .= implode( '</span><span class="successbox">', $headerLinks );
		$header .= '</span>';

		switch ( $requestedMode ) {
			case 'httpdinfo':
				$body = file_get_contents( 'http://127.0.0.1:8090/server-info?' . $webRequest->getVal( 'submode', '' ) );
				$submodes = [
					'config'    => 'Configuration Files',
					'server'    => 'Server Settings',
					'list'      => 'Module List',
					'hooks'     => 'Active Hooks',
					'providers' => 'Available Providers',
				];
				foreach ( $submodes as $submode => $linkText ) {
					$body = str_replace(
						"<a href=\"?$submode\">$linkText</a>",
						Linker::link(
							$this->getPageTitle(),
							$linkText,
							[], // custom attributes
							[ 'mode' => 'httpdinfo', 'submode' => $submode ]
						),
						$body
					);
				}
				break;

			case 'phpinfo':
				ob_start();
				phpinfo();
				$body = ob_get_contents();
				ob_clean();
				break;

			default:
				$body = file_get_contents( 'http://127.0.0.1:8090/server-status' );
				break;
		}

		$output = $this->getOutput();
		$output->setPageTitle(
			$this->msg( strtolower( $this->mName ) )->text()
		);
		$output->addHTML( $header . $body );

	}

}

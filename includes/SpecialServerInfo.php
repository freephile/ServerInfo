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

	function execute( $param ) {
		// Only allow users with 'viewserverinfo' right (sysop by default) to access
		$user = $this->getUser();
		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
			return;
		}

		$output = $this->getOutput();
		$output->enableOOUI();

		$webRequest = $this->getRequest();
		// Default to phpinfo
		$requestedMode = $webRequest->getVal( 'mode', 'phpinfo' );

		$modes = [ 'httpdstatus', 'httpdinfo', 'phpinfo', 'clockinfo' ];

		$headerLinks = [];
		foreach ( $modes as $mode ) {
			$linkText = $this->msg( 'serverinfo-mode-' . $mode )->text();

			if ( $mode === $requestedMode ) {
				$headerLinks[] = new OOUI\ButtonWidget( [
					'label' => "$linkText"
				] );
			} else {
				$headerLinks[] = new OOUI\ButtonWidget( [
					'label' => "$linkText",
					'href' => $this->getPageTitle() . "?mode=$mode"
				] );
			}
		}

		$header = '<div id="serverinfonav" class="container" style="display: flex; justify-content: center;">';
		$header .= implode( '&nbsp;', $headerLinks );
		$header .= '</div>';

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

			case 'clockinfo':

				$availableClocks = shell_exec( 'cat /sys/devices/system/clocksource/clocksource0/available_clocksource 2>&1' );
				$currentClock = shell_exec( 'cat /sys/devices/system/clocksource/clocksource0/current_clocksource 2>&1' );
				$cpuInfo = shell_exec( 'cat /proc/cpuinfo' );
				preg_match( '/tsc/', $cpuInfo, $matches );
				$supportedClocks = $matches[0];

				$strace = shell_exec( 'strace php -m 2>&1 | grep gettimeofday' );
				$vdsoEnabled = preg_match( '/gettimeofday/', $strace );

				$body = '<div class="container">';
				$body .= '<h2>' . $this->msg( 'serverinfo-available-clocks' )->text() . '</h2>';
				$body .= $availableClocks;
				$body .= '<h2>' . $this->msg( 'serverinfo-current-clock' )->text() . '</h2>';
				$body .= $currentClock;
				$body .= '<h2>' . $this->msg( 'serverinfo-supported-clocks' )->text() . '</h2>';
				$body .= $supportedClocks;
				$body .= '<h2>' . $this->msg( 'serverinfo-vdso-enabled' )->text() . '</h2>';
				$body .= $vdsoEnabled;
				$body .= '</div>';

				break;

			default:
				$body = file_get_contents( 'http://127.0.0.1:8090/server-status' );
				break;
		}

		$output->setPageTitle(
			$this->msg( strtolower( $this->mName ) )->text()
		);
		$output->addHTML( $header . $body );
	}

}

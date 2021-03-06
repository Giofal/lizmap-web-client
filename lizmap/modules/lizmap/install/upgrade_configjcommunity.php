<?php
class lizmapModuleUpgrader_configjcommunity extends jInstallerModule {

    public $targetVersions = array(
        '3.2pre.180212'
    );
    public $date = '2018-02-12';

    function install() {

        if( $this->firstExec('configchange') ) {
            $lzmIni = new jIniFileModifier(jApp::configPath('lizmapConfig.ini.php'));

            $localIni = $this->entryPoint->localConfigIni->getMaster();

            $val = $lzmIni->getValue('allowUserAccountRequests', 'services');
            if ($val === null) {
                $val = false;
            }
            else {
                $lzmIni->removeValue('allowUserAccountRequests', 'services');
            }
            $localIni->setValue('registrationEnabled', ($val?'on':'off'), 'jcommunity');

            $adminSenderEmail = $this->entryPoint->config->mailer['webmasterEmail'];
            if ($adminSenderEmail == 'root@localhost') {
                $adminSenderEmail = '';
            }

            $val = $lzmIni->getValue('adminContactEmail', 'services');
            if ($val !== null && $adminSenderEmail == '') {
                $localIni->setValue('webmasterEmail', $val,  'mailer');
            }
            $lzmIni->save();
            $localIni->save();
        }
    }

}

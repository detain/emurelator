<?php
namespace App\Topic;

use CLIFramework\Topic\BaseTopic;

class ExamplesTopic extends BaseTopic {
	public $title = 'Example Command Lines';
	public $url = 'https://github.com/interserver/vps_host_server';

    public function getContent() {
    	return
'        Command Examples
            emurelator create vps100 162.246.19.201 ubuntu12 50 4096 4 p4ssw0rd
            emurelator create --add-ip=162.246.19.202 --client-ip=70.44.33.193 vps100 162.246.19.201 ubuntu-20.04 100 4096 4 p4ssw0rd
            emurelator create -i 208.73.201.161 -i 208.73.201.162 -i 208.73.201.163 -c 70.44.33.193 vps100 208.73.201.160 centos5 100 4096 4 p4ssw0rd
            emurelator block-smtp vps100 100
            emurelator block-smtp vps100
            emurelator change-timezone vps100 America/New_York
            emurelator stop vps100
            emurelator start vps100
            emurelator restart vps100
            emurelator setup-vnc vps100 70.44.33.193
            emurelator remove-ip vps100 162.246.19.202
            emurelator add-ip vps100 162.246.19.202
            emurelator delete vps100
            emurelator enable vps100
            emurelator destroy vps100
            emurelator disable-cd vps100
            emurelator enable-cd vps100 https://mirror.trouble-free.net/iso/KNOPPIX_V7.2.0CD-2013-06-16-EN.iso
            emurelator eject-cd vps100
            emurelator insert-cd vps100 https://mirror.trouble-free.net/iso/KNOPPIX_V7.2.0CD-2013-06-16-EN.iso
            emurelator change-hostname vps100 vps101
            emurelator update-hdsize vps100 150

            emurelator reset-password vps100
            emurelator backup vps101 101 detain@interserver.net
            emurelator restore vps101 vps101-2021-09-18-13450.zst vps101 101


        Contributing
            Got a great example and feel it should be included? Submit a pull request or issue.';
    }

	//public function getFooter() {}
}



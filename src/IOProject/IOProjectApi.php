<?php namespace IOProject;

class IOProjectApi {

    public function getEvent() {

        $event = filter_input(INPUT_GET, 'a');
        if ($event == 'logon') {
            return 1;
        } else if ($event == 'logout') {
            return 2;
        } else if ($event == 'register') {
            return 3;
        } 
        return 0;
    }
}
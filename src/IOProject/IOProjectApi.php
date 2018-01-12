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
        } else if ($event == 'add-employee') {
            return 4;
        } else if ($event == 'edit-employee') {
            return 5;
        } else if ($event == 'del-employee') {
            return 6;
        }
        return 0;
    }
}
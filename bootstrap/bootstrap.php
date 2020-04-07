<?php

class erLhcoreClassExtensionLhccollectcustombot {

    public function __construct() {

    }

    public function run() {

        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();

        // Handle generic messages payload
        $dispatcher->listen('chat.genericbot_event_handler', array($this,'genericHandlerEvent'));
    }

    public function genericHandlerEvent($params)
    {
        if ($params['render'] == 'order_number') {
            return array(
                'status' => erLhcoreClassChatEventDispatcher::STOP_WORKFLOW,
                'render' => 'erLhcoreClassExtensionLhccollectcustombot::validateOrderNumber',
                'render_args' => array($params)
            );
        }
    }

    public static function validateOrderNumber($params) {

        $metaMessage = array();

        if (isset($params['event_data']['content']['attr_options']['cancel_button_enabled']) && $params['event_data']['content']['attr_options']['cancel_button_enabled'] == true) {
            $metaMessage = array('content' =>
                array (
                    'quick_replies' =>
                        array (
                            0 =>
                                array (
                                    'type' => 'button',
                                    'content' =>
                                        array (
                                            'name' => (($params['event_data']['content']['cancel_button'] && $params['event_data']['content']['cancel_button'] != '') ? $params['event_data']['content']['cancel_button'] : 'Cancel?'),
                                            'payload' => 'cancel_workflow',
                                        ),
                                )
                        ),
                ));
        }

        return array(
            'valid' => false,
            'message' => 'Validating order number failed' . $params['payload'],
            'params_exception' => $metaMessage
        );
    }

    public function __get($var) {
        switch ($var) {

            case 'settings' :
                $this->settings = include ('extension/lhccollectcustombot/settings/settings.ini.php');
                return $this->settings;
                break;

            default :
                ;
                break;
        }
    }
}



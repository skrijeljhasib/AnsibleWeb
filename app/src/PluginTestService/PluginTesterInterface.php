<?php
namespace Project\PluginTestService;

interface PluginTesterInterface{
    /**
     * @return mixed
     */
    function loggerTester();
    /**
     * @return mixed
     */
    function auditTester();
    /**
     * @return mixed
     */
    function mailerTester();
    /**
     * @return mixed
     */
    function filerTester();
}

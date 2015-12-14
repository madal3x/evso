<?php

require_once dirname(__FILE__)."/Autoloader.php";

$handler = new \connected\common\domain\model\CommandHandler(new \connected\reservation\application\room\command\handler\factory\CommandHandlerFactory());
//$handler->handle(new \connected\reservation\application\room\command\CreateRoomCommand((new \connected\common\domain\model\UUID())->value(), 0, 'room1', 2));
//$handler->handle(new \connected\reservation\application\room\command\CreateRoomCommand(0, 'room2', 3));
$handler->handle(new \connected\reservation\application\room\command\BookSeatCommand(1, 'e2a86d3e3f3b3492e3fa5b705362264a'));
//$handler->handle(new BookSeatCommand(1, '561a71d66649a9.80427864'));
//$handler->handle(new \connected\reservation\application\room\command\ChangeRoomNameCommand(1, 'e2a86d3e3f3b3492e3fa5b705362264a', 'rooooooooooooom1'));
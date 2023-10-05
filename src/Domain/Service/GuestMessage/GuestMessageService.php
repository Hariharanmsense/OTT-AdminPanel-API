<?php

namespace App\Domain\Service\GuestMessage;

interface GuestMessageService
{
   /**
    * Summary of getguestmsglist
    * @param mixed $JWTdata
    * @param mixed $userId
    * @param mixed $userName
    * @return void
    */
   public function  getguestmsglist($JWTdata,$userId,$userName);
   /**
    * Summary of sendmessage
    * @param mixed $input
    * @param mixed $msg_img
    * @param mixed $userId
    * @param mixed $userName
    * @return void
    */
   public function sendmessage($input,$msg_img,$userId,$userName);
   /**
    * Summary of getoneRoomRepository
    * @param mixed $input
    * @param mixed $msg_id
    * @param mixed $userId
    * @param mixed $userName
    * @return void
    */
   public function getoneRoomRepository($input,$msg_id,$userId,$userName);

   /**
    * Summary of guestmsgdelete
    * @param mixed $input
    * @param mixed $msg_id
    * @param mixed $userId
    * @param mixed $userName
    * @return void
    */
   public function guestmsgdelete($input,$msg_id,$userId,$userName);
}

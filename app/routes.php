<?php

declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;
use App\Application\Middleware\AuthMiddleware;

use App\Application\Actions\Login\LoginAction;
use App\Application\Actions\Brand\BrandAction;
use App\Application\Actions\Hotel\HotelAction;
use App\Application\Actions\Common\CommonAction;
use App\Application\Actions\TV\TvAction;
use App\Application\Actions\Channel\ChannelAction;
use App\Application\Actions\Channel\ChannelCategory\CategoryAction;
use App\Application\Actions\Channel\HotelChannelAction;
use App\Application\Actions\Room\RoomAction;
use App\Application\Actions\Tvsolution\TVtempAction;
use App\Application\Actions\Device\DeviceAction;
use App\Application\Actions\WizardSetup\WizardSetupAction;
// use App\Application\Actions\User\ViewUserAction;
// use App\Application\Actions\User\ListUsersAction;
use App\Application\Auth\JwtToken;
use App\Application\Actions\AdmnGrps\AdmnGrpsAction;
use App\Application\Actions\AdmnUsrs\AdmnUsrsAction;
use App\Application\Actions\GuestMessage\GuestMessageAction;

return function (App $app) {
    $app->get('/', function(Request $request,Response $response){
         $response->getBody()->write('Hello world');
        return $response;
    });
    $app->post('/login', LoginAction::class);
	$app->post('/forgotpassword', AdmnUsrsAction::class.':forgotPassword');
    $app->post('/resetpassword', AdmnUsrsAction::class.':resetPassword');
	$app->get('/resetpassword/{id}', AdmnUsrsAction::class.':resetPasswordGet');
	
	$app->group('/common', function (Group $group) {
        $group->get('/all-group', CommonAction::class.':getAllGroup')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->get('/all-brand', CommonAction::class.':getAllBrand')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-hotel', CommonAction::class.':getAllHotel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/all-availablemenus', CommonAction::class.':getAllAvailableMenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/all-assignmenus', CommonAction::class.':getAllAssignMenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-rwaccessmenus', CommonAction::class.':getAllReadWriteMenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-timezone', CommonAction::class.':getAllTimeZone')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-sidebarmenus', CommonAction::class.':getAllSideBarMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-menurightaccess/{id}', CommonAction::class.':getMenuRightAccess')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devices/{id}', CommonAction::class.':getAllDevices')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-interface-type', CommonAction::class.':getAllIneterfaceType')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicestatus', CommonAction::class.':getAllDeviceStatus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicelocations', CommonAction::class.':getAllDeviceLocations')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicetypes', CommonAction::class.':getAllDeviceTypes')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/all-icmppolicys', CommonAction::class.':getAllIcmpPolicys')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-internets/{id}', CommonAction::class.':getAllInternetLists')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-submenus', CommonAction::class.':getAllSubmenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-allowedhotels/{id}', CommonAction::class.':getAllallowedHotels')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-allowedbrands', CommonAction::class.':getAllallowedBrands')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/all-usermenuright', CommonAction::class.':getMenuRightsByHotelMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-allowedgrphotel/{id}', CommonAction::class.':getAllAllowedHotelByGroup')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-avaliablegrphotel/{id}', CommonAction::class.':getAllAvaliableHotelByGroup')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-latesthotels', CommonAction::class.':getAlllatestHotels')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->get('/all-ottsidemenu', CommonAction::class.':getAllOttSideMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    /* $app->group('/common', function (Group $group) {
        $group->get('/all-brand', CommonAction::class.':getAllBrand')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-hotel', CommonAction::class.':getAllHotel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-assignmenus', CommonAction::class.':getAllAssignMenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-rwaccessmenus', CommonAction::class.':getAllReadWriteMenus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-timezone', CommonAction::class.':getAllTimeZone')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-sidebarmenus', CommonAction::class.':getAllSideBarMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-menurightaccess/{id}', CommonAction::class.':getMenuRightAccess')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devices', CommonAction::class.':getAllDevices')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicestatus', CommonAction::class.':getAllDeviceStatus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicelocations', CommonAction::class.':getAllDeviceLocations')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-devicetypes', CommonAction::class.':getAllDeviceTypes')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-icmppolicys', CommonAction::class.':getAllIcmpPolicys')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/all-ottsidemenu', CommonAction::class.':getAllOttSideMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });*/

	$app->group('/admngrps', function (Group $group) {
        $group->post('/list', AdmnGrpsAction::class.':getGrpList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', AdmnGrpsAction::class.':getGrpOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('', AdmnGrpsAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', AdmnGrpsAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/export-excel', AdmnGrpsAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/cgrpasnmnu', AdmnGrpsAction::class.':createGroupAssginMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/egrpasnmnu', AdmnGrpsAction::class.':editGroupAssginMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/deletegrp/{id}', AdmnGrpsAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

     $app->group('/admnusrs', function (Group $group) {      
        $group->post('/list', AdmnUsrsAction::class.':getusrsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', AdmnUsrsAction::class.':getUsrOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('', AdmnUsrsAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', AdmnUsrsAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/pwdreset', AdmnUsrsAction::class.':getusrsPwdReset')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/ownpwdreset', AdmnUsrsAction::class.':getusrsOwnPwdReset');
        $group->post('/profileupdate', AdmnUsrsAction::class.':profileUpdate')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/statusupdate/{id}', AdmnUsrsAction::class.':activeOrDeactiveUser')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));       
        $group->post('/logout', LoginAction::class.':logOut')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/export-excel', AdmnUsrsAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/deleteusr/{id}', AdmnUsrsAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/old-password', AdmnUsrsAction::class.':getOldpassword')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/lastlogin', AdmnUsrsAction::class.':getLastLogin')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/brand', function(Group $group){
        $group->post('/list',BrandAction::class)->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',BrandAction::class.':AddBrandAction')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', BrandAction::class.':EditViewAction')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', BrandAction::class.':UpdateBrandAction')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', BrandAction::class.':DeleteBrandAction')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/actordeact/{id}', BrandAction::class.':activeordeactive')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('/export-excel', BrandAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/customer', function(Group $custGrp){
        $custGrp->post('/list',HotelAction::class.':gethotelsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->post('',HotelAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->get('/{id}', HotelAction::class.':gethotelOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->put('/{id}', HotelAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->delete('/{id}', HotelAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$custGrp->put('/statusupdate/{id}', HotelAction::class.':activeOrDeactive')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->put('/icmpstatus/{id}', HotelAction::class.':icmpStatus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->put('/alertemlstatus/{id}', HotelAction::class.':alertEmailStatus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->put('/bwstatus/{id}', HotelAction::class.':bwStatus')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->post('/export-excel', HotelAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $custGrp->post('/gnrtehtlcde', HotelAction::class.':gnrteHtlCde')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/tv', function(Group $group){
        $group->post('/list',TvAction::class.':gettvlist')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',TvAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', TvAction::class.':getOneTvdetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', TvAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', TvAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/channel', function(Group $channelGrp){
        $channelGrp->post('/list',ChannelAction::class.':getchannelsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $channelGrp->post('',ChannelAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $channelGrp->get('/{id}', ChannelAction::class.':getOnechanneldetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $channelGrp->post('/{id}', ChannelAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $channelGrp->delete('/{id}', ChannelAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $channelGrp->post('/export/excel', ChannelAction::class.':channelExcel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });
  
    $app->group('/channelcategory', function(Group $group){
        $group->post('/list',CategoryAction::class.':categoryList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',CategoryAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', CategoryAction::class.':getOneategorydetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', CategoryAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', CategoryAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/asgnedchnls', CategoryAction::class.':assignedchannel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/avlchnls', CategoryAction::class.':availablechannel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/export/excel', CategoryAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    
    $app->group('/hotelchannel', function(Group $group){
        $group->post('/list',HotelChannelAction::class)->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',HotelChannelAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', HotelChannelAction::class.':getOneHotelchannel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', HotelChannelAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', HotelChannelAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('/asgnchnl', HotelChannelAction::class.':assginMenu')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('/commonchannel', HotelChannelAction::class.':getOverallchannellist')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/room', function(Group $group){
        $group->post('/list',RoomAction::class)->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',RoomAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', RoomAction::class.':getOneroom')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', RoomAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', RoomAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });
	
	$app->group('/tvsolution', function(Group $group){
        $group->get('/list',TVtempAction::class.':getalltemplates')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->get('/channelfeed',TVtempAction::class.':getallchannelfeed')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->get('/features',TVtempAction::class.':getallfeatures')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/homescreen/{id}',TVtempAction::class.':getJsonObjects')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });
    
    $app->group('/device-details', function (Group $group) {
        $group->post('/list', DeviceAction::class.':getDeviceList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', DeviceAction::class.':getUsrOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('', DeviceAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', DeviceAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/bulk/upload', DeviceAction::class.':bulkUpload')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/wizardsetup', function(Group $group){
        $group->put('/template', WizardSetupAction::class.':updatetemplate')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('', WizardSetupAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/homescreen', WizardSetupAction::class.':GenerateJsonFile')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/overview', WizardSetupAction::class.':GetTemplateDetails')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/feed', WizardSetupAction::class.':updatefeedtype')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/bulk/upload', WizardSetupAction::class.':bulkUpload')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/menusetup', WizardSetupAction::class.':menusetup')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/guest', WizardSetupAction::class.':GuestService')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/hotel', WizardSetupAction::class.':HotelService')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));

    });
	
	 $app->group('/tvproject', function(Group $group){
        $group->post('/commonchannel', HotelChannelAction::class.':getOverallchannellist')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/GuestService', WizardSetupAction::class.':getGuestServiceJson')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/homescreen', WizardSetupAction::class.':GetHomescreenJson')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/guestmsg', function (Group $guestmsg) {
        $guestmsg->post('/list', GuestMessageAction::class.':getguestmsgList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $guestmsg->post('', GuestMessageAction::class.':sendmessage')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $guestmsg->get('/{id}', GuestMessageAction::class.':getoneRoom')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $guestmsg->delete('/{id}', GuestMessageAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        //$guestmsg->post('/export-excel', GuestMessageAction::class.':excel')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    
};

	


//old code needed for reference
/*

    $app->get('/', function (Request $request, Response $response) {

        $loggerHandler = $this->get(LoggerFactory::class);
		$objLogger = $loggerHandler->addFileHandler('mifi.log')->createInstance('routes');
        $objLogger->info('Hi i am karthik');
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    */
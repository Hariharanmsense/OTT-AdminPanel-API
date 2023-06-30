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

return function (App $app) {
    $app->get('/', function(Request $request,Response $response){
         $response->getBody()->write('Hello world');
        return $response;
    });
    $app->post('/login', LoginAction::class);
	$app->post('/forgotpassword', AdmnUsrsAction::class.':forgotPassword');
    $app->post('/resetpassword', AdmnUsrsAction::class.':resetPassword');

     $app->group('/common', function (Group $group) {
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
    });
	
	$app->group('/admngrps', function (Group $group) {
        $group->post('/list', AdmnGrpsAction::class.':getGrpList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', AdmnGrpsAction::class.':getGrpOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('', AdmnGrpsAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', AdmnGrpsAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

     $app->group('/admnusrs', function (Group $group) {
        $group->post('/list', AdmnUsrsAction::class.':getusrsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', AdmnUsrsAction::class.':getUsrOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		$group->post('', AdmnUsrsAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', AdmnUsrsAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/pwdreset/{id}', AdmnUsrsAction::class.':getusrsPwdReset')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
		 $group->put('/statusupdate/{id}', AdmnUsrsAction::class.':activeOrDeactiveUser')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));       

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

    $app->group('/customer', function(Group $group){
        $group->post('/list',HotelAction::class.':gethotelsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',HotelAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', HotelAction::class.':gethotelOne')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', HotelAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', HotelAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/tv', function(Group $group){
        $group->post('/list',TvAction::class.':gettvlist')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',TvAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', TvAction::class.':getOneTvdetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', TvAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', TvAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/channel', function(Group $group){
        $group->post('/list',ChannelAction::class.':getchannelsList')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',ChannelAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', ChannelAction::class.':getOnechanneldetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('/{id}', ChannelAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', ChannelAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
    });

    $app->group('/channelcategory', function(Group $group){
        $group->post('/list',CategoryAction::class)->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->post('',CategoryAction::class.':create')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->get('/{id}', CategoryAction::class.':getOneategorydetail')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->put('/{id}', CategoryAction::class.':update')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
        $group->delete('/{id}', CategoryAction::class.':delete')->add(new AuthMiddleware($this->get(JwtToken::class), $this->get(DBConFactory::class)));
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
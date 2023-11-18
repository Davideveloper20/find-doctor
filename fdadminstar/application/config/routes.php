<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['Validations/EmailSignUp'] = 'index/validateEmailSignUp';

$route['Auth/Register']['get'] = "Auth/register";
$route['Auth/Register']['post'] = "Auth/signUp";
$route['Auth/Login']['get'] = "Auth/login";
$route['Auth/Login']['post'] = "Auth/signin";
$route['Auth/Olvido-Clave']['get'] = "Auth/forgot_password";
$route['Auth/Forgot-Password']['get'] = "Auth/forgot_password";
$route['Auth/Olvido-Clave']['post'] = "Auth/ftpassword";
$route['Auth/Forgot-Password']['post'] = "Auth/ftpassword";
$route['Auth/Restablecer-Clave/(.*)']['get'] = "Auth/reset_password/$1";
$route['Auth/Restablecer-Clave']['post'] = "Auth/resetpassword";

$route['Patient/Search/(.*)/(.*)']['get'] = "Doctor/searchpatient/$1/$2";
$route['Patient/Search/(.*)/(.*)']['post'] = "Doctor/searchpatient/$1/$2";
$route['Doctor']['get'] = "Doctor/index";
$route['Doctor/Profile']['get'] = "Doctor/index";
$route['Doctor/Perfil']['get'] = "Doctor/index";

$route['Doctor']['post'] = "Doctor/updateprofile";
$route['Doctor/Profile']['post'] = "Doctor/updateprofile";
$route['Doctor/Perfil']['post'] = "Doctor/updateprofile";

$route['Doctor/Goals']['get'] = "Doctor/goals";
$route['Doctor/Logros']['get'] = "Doctor/goals";
$route['Doctor/GoalSave']['put'] = "Doctor/updategoal";
$route['Doctor/GoalSave']['post'] = "Doctor/savegoal";
$route['Doctor/GoalSave']['patch'] = "Doctor/getgoal";
$route['Doctor/GoalSave']['delete'] = "Doctor/deletegoal";

$route['Doctor/Comments']['get'] = "Doctor/valorations";
$route['Doctor/Comments']['post'] = "Doctor/get_valorations";

$route['Doctor/Formation']['get'] = "Doctor/formation";
$route['Doctor/Formacion']['get'] = "Doctor/formation";

$route['Doctor/FormationSave']['put'] = "Doctor/updateformation";
$route['Doctor/FormationSave']['post'] = "Doctor/saveformation";
$route['Doctor/FormationSave']['patch'] = "Doctor/getformation";
$route['Doctor/FormationSave']['delete'] = "Doctor/deleteformation";

$route['Doctor/Specialities']['get'] = "Doctor/specialities";
$route['Doctor/Especialidades']['get'] = "Doctor/specialities";
$route['Doctor/SpecialitiesSave']['put'] = "Doctor/updatespecialities";
$route['Doctor/SpecialitiesSave']['post'] = "Doctor/savespecialities";
$route['Doctor/SpecialitiesSave']['patch'] = "Doctor/getspecialities";
$route['Doctor/SpecialitiesSave']['delete'] = "Doctor/deletespecialities";

$route['Doctor/Services']['get'] = "Doctor/services";
$route['Doctor/Servicios']['get'] = "Doctor/services";
$route['Doctor/ServicesSave']['put'] = "Doctor/updateservices";
$route['Doctor/ServicesSave']['post'] = "Doctor/saveservices";
$route['Doctor/ServicesSave']['patch'] = "Doctor/getservices";
$route['Doctor/ServicesSave']['delete'] = "Doctor/deleteservices";

$route['Doctor/EPS-Seguros']['get'] = "Doctor/doctor_epsseguros";
//$route['Doctor/EPS-Seguros']['get'] = "Doctor/doctor_epsseguros";
$route['Doctor/EPS-SegurosSave']['post'] = "Doctor/savedoctor_epsseguros";
$route['Doctor/EPS-SegurosSave']['put'] = "Doctor/updatedoctor_epsseguros";
$route['Doctor/EPS-SegurosSave']['delete'] = "Doctor/deletedoctor_epsseguros";

$route['Doctor/Consulting-Room']['get'] = "Doctor/consultingroom";
$route['Doctor/PreSchedule']['get'] = "Doctor/preSchedule";
$route['Doctor/Consultorios']['get'] = "Doctor/consultingroom";
$route['Doctor/ConsultoriosSave']['post'] = "Doctor/saveconsultingroom";
$route['Doctor/Consulting-Room-Save']['post'] = "Doctor/saveconsultingroom";
$route['Doctor/Consulting-Room-Save']['delete'] = "Doctor/delconsultingroom";

$route['Doctor/ChatStatus/(.*)']['post'] = "Doctor/chatStatuss2/$1";

$route['Doctor/Settings']['get'] = "Doctor/settingsdoc";
$route['Doctor/Configuraciones']['get'] = "Doctor/settingsdoc";
$route['Doctor/SettingsSave']['post'] = "Doctor/savesettingsdoc";

$route['Doctor/Appoinments']['get'] = "Doctor/appoinments";
$route['Doctor/Citas']['get'] = "Doctor/appoinments";
$route['AppoinmentsSave']['post'] = "Doctor/saveappoinments";
$route['Doctor/AppoinmentsSave']['post'] = "Doctor/saveappoinments";
$route['Doctor/AppoinmentsSave/(.*)']['put'] = "Doctor/updappoinments/$1";
$route['Doctor/AppoinmentsSave/(.*)']['delete'] = "Doctor/deleteappoinments/$1";
$route['Doctor/AppoinmentsList']['get'] = "Doctor/getDocAppoints";

$route['Consultorios']['get'] = "Doctor/conf_consulting";
$route['ConsultoriosSave']['patch'] = "Doctor/getconf_consulting";
$route['ConsultoriosSave']['post'] = "Doctor/saveconf_consulting";
$route['ConsultoriosSave']['put'] = "Doctor/updateconf_consulting";
$route['ConsultoriosSave']['get'] = "Doctor/conf_consultingDt";

$route['l/(.*)/(.*)/(.*)'] = "Doctor/localtion/$1/$2/$3";

$route['Specialities']['get'] = "Doctor/conf_specialities";
$route['Especialidades']['get'] = "Doctor/conf_specialities";
$route['SpecialitiesSave']['get'] = "Doctor/conf_specialitiesDt";
$route['SpecialitiesSave']['put'] = "Doctor/updateconf_specialities";
$route['SpecialitiesSave']['post'] = "Doctor/saveconf_specialities";
$route['SpecialitiesSave']['patch'] = "Doctor/getconf_specialities";
$route['SpecialitiesSave']['delete'] = "Doctor/deleteconf_specialities";

$route['EPS-Seguros']['get'] = "Doctor/conf_epsseguros";
//$route['Especialidades']['get'] = "Doctor/conf_specialities";
$route['EPS-SegurosSave']['get'] = "Doctor/conf_epssegurosDt";
$route['EPS-SegurosSave']['put'] = "Doctor/updateconf_epsseguros";
$route['EPS-SegurosSave']['post'] = "Doctor/saveconf_epsseguros";
$route['EPS-SegurosSave']['patch'] = "Doctor/getconf_epsseguros";
$route['EPS-SegurosSave']['delete'] = "Doctor/deleteconf_epsseguros";

$route['Doctor/setMedPrepaid/(.*)']['post'] = "Doctor/setdoc_epsseguros/$1";
$route['Doctor/Chat-Room']['get'] = "Doctor/docChat";
$route['Doctor/Get-Chat-Messages/(:num)/(:num)/(:num)']['post'] = "Doctor/getChatMessages/$1/$2/$3";
$route['Doctor/Send-Chat-Messages/(:num)']['post'] = "Doctor/sendChatMessages/$1";
$route['getCities/(:num)']['post'] = "Dashboard/getCities/$1";
$route['getCities/(:num)/(:num)']['post'] = "Dashboard/getCities/$1/$2";


$route['Doctor/Patients-Own']['get'] = "Doctor/mypatientes";
$route['Doctor/Patients-App']['get'] = "Doctor/mypatientesapp";
$route['Doctor/DocPatientChat']['post'] = "Doctor/DocPatientChat";
$route['Doctor/Pacientes']['get'] = "Doctor/mypatientes";
$route['Doctor/PacientesSave']['post'] = "Doctor/savepatient";
$route['Doctor/PatientsSave']['post'] = "Doctor/savepatient";

$route['Admin/Doctors']['get'] = "Doctor/admin_doctors";
$route['Admin/Doctors']['post'] = "Doctor/admin_lstdoctors";
$route['Admin/DoctorsChgStatus/(:num)']['post'] = "Doctor/admin_setdoctors/$1";

$route['Admin/Req-Payments']['get'] = "Doctor/admin_reqpays";
$route['Admin/Req-Payments']['post'] = "Doctor/admin_lstreqpays";

$route['Admin/Patients-User']['get'] = "Doctor/admin_patients";
$route['Admin/Patients-Users']['get'] = "Doctor/admin_patients";
$route['Admin/Patients-User']['post'] = "Doctor/admin_lstpatients";
$route['Admin/Patients-Users']['post'] = "Doctor/admin_lstpatients";
$route['Admin/PatientChgStatus/(:num)']['post'] = "Doctor/admin_setpatientstat/$1";

$route['Admin/Patients-NoUsers']['get'] = "Doctor/admin_nopatients";
$route['Admin/Patients-NoUsers']['post'] = "Doctor/admin_lstnopatients";
$route['Admin/Doctor-Save']['patch'] = "Doctor/getDoctorData";
$route['Admin/Doctor-Save']['put'] = "Doctor/putDoctorData";


$route['Doctor/DocPatientChatLst']['post'] = "Doctor/chatlastdocs";
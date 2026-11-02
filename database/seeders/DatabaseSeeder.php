<?php

use App\ModuleRegisterPageDetail;
use App\Ohs;
use App\User;
use App\UserGate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        $this->call([
            CategorySeeder::class,
            ConfigSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            UserGateSeeder::class,
            CompanySizeSeeder::class,
            CategoryBasedFieldSeeder::class,
            HmlSeeder::class,
            IncidentPreventionSeeder::class,
            IncidentTypeSeeder::class,
            MailSeeder::class,
            ModenadisableSeeder::class,
            ModpageloadSeeder::class,
            ModuleRegisterPageDetailSeeder::class,
            ModuleMyOpenPageDetailSeeder::class,
            ModuleClosedPageDetailSeeder::class,
            PartiesSeeder::class,
            PrioritiesSeeder::class,
            ProtectionKitSeeder::class,
            RiskNamesSeeder::class,
            RiskStrategiesSeeder::class,
            RiskGroupDetailsSeeder::class,
            DepartmentDetailsSeeder::class,
            ScopeSeeder::class,
            SignupLimitSeeder::class,
            YearsSeeder::class,
            TypesSeeder::class,
            SuperAdminsSeeder::class,
            VendordetailsSeeder::class,
            CustomerdetailSeeder::class,
            VendorresultSeeder::class,
            WasteSeeder::class,
            WasteDispoalMethodsSeeder::class,
            HealthCategorySeeder::class,
            HealthTreatmentSeeder::class,
            InjuryWorkSeeder::class,
            IllnessNonWorkSeeder::class,
            ManagementAgendaTypeSeeder::class,
            ManagementAgendaPointsSeeder::class,
            ManagementAgendaPointListSeeder::class,
            ManagementReviewLocationSeeder::class,
            EmergencyResponseSituationSeeder::class,
            EmergencyResponseProblemdetailsSeeder::class,
            EmergencyResponseMitigationdetailsSeeder::class,
            EmailNotificationSeeder::class,
            OpportunityTypeSeeder::class,
            PotentialLossSeeder::class,
            ContextCrsSeveritiesSeeder::class,
            StandardsSeeder::class,
            ConformanceSeeder::class,
            AuditRootCorrectiveSeeder::class,
            CorrectiveRootCauseCorrectiveActionDetailSeeder::class,
            EnvironmentalImpactDetailSeeder::class,
            OhsRiskownerSeeder::class,
            ObjectiveTypeSeeder::class,
            EnviaspectProbabilitySeeder::class,
            EnviaspectConsequenceSeeder::class,
            EnviaspectRegulatoryRequirementSeeder::class,
            EnviaspectQuantitySeeder::class,
            EnviaspectControlMeasureSeeder::class,
            ModuleBladePageDetailSeeder::class,
            ModifyDocumentTypeSeeder::class,
            ProductNcrConfigurationFieldsSeeder::class,
            ProcessNcrConfigurationFieldsSeeder::class,
            VendorNcrConfigurationFieldsSeeder::class,
            NcrFieldHistorySeeder::class,
            DetectionStageSeeder::class,
            ComplaintTemplateSeeder::class,
            ComplaintTemplateSectionSeeder::class,
            ComplaintTemplateSectionFieldSeeder::class,
            ComplaintTemplateSectionFieldMetaSeeder::class,
            AppConfigurationSeeder::class
        ]);
        Model::reguard();
    }
}
class CountrySeeder extends Seeder
{
    public function addPupilKeys($array)
    {
        return [
            'id' => $array[0],
            'sort' => $array[1],
            'country_name' => $array[2],
            'phoneCode' => $array[3],
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        $countries = [
            $this->addPupilKeys([1, 'AF', 'Afghanistan', 93]),
            $this->addPupilKeys([2, 'AL', 'Albania', 355]),
            $this->addPupilKeys([3, 'DZ', 'Algeria', 213]),
            $this->addPupilKeys([4, 'AS', 'American Samoa', 1684]),
            $this->addPupilKeys([5, 'AD', 'Andorra', 376]),
            $this->addPupilKeys([6, 'AO', 'Angola', 244]),
            $this->addPupilKeys([7, 'AI', 'Anguilla', 1264]),
            $this->addPupilKeys([8, 'AQ', 'Antarctica', 0]),
            $this->addPupilKeys([9, 'AG', 'Antigua And Barbuda', 1268]),
            $this->addPupilKeys([10, 'AR', 'Argentina', 54]),
            $this->addPupilKeys([11, 'AM', 'Armenia', 374]),
            $this->addPupilKeys([12, 'AW', 'Aruba', 297]),
            $this->addPupilKeys([13, 'AU', 'Australia', 61]),
            $this->addPupilKeys([14, 'AT', 'Austria', 43]),
            $this->addPupilKeys([15, 'AZ', 'Azerbaijan', 994]),
            $this->addPupilKeys([16, 'BS', 'Bahamas The', 1242]),
            $this->addPupilKeys([17, 'BH', 'Bahrain', 973]),
            $this->addPupilKeys([18, 'BD', 'Bangladesh', 880]),
            $this->addPupilKeys([19, 'BB', 'Barbados', 1246]),
            $this->addPupilKeys([20, 'BY', 'Belarus', 375]),
            $this->addPupilKeys([21, 'BE', 'Belgium', 32]),
            $this->addPupilKeys([22, 'BZ', 'Belize', 501]),
            $this->addPupilKeys([23, 'BJ', 'Benin', 229]),
            $this->addPupilKeys([24, 'BM', 'Bermuda', 1441]),
            $this->addPupilKeys([25, 'BT', 'Bhutan', 975]),
            $this->addPupilKeys([26, 'BO', 'Bolivia', 591]),
            $this->addPupilKeys([27, 'BA', 'Bosnia and Herzegovina', 387]),
            $this->addPupilKeys([28, 'BW', 'Botswana', 267]),
            $this->addPupilKeys([29, 'BV', 'Bouvet Island', 0]),
            $this->addPupilKeys([30, 'BR', 'Brazil', 55]),
            $this->addPupilKeys([31, 'IO', 'British Indian Ocean Territory', 246]),
            $this->addPupilKeys([32, 'BN', 'Brunei', 673]),
            $this->addPupilKeys([33, 'BG', 'Bulgaria', 359]),
            $this->addPupilKeys([34, 'BF', 'Burkina Faso', 226]),
            $this->addPupilKeys([35, 'BI', 'Burundi', 257]),
            $this->addPupilKeys([36, 'KH', 'Cambodia', 855]),
            $this->addPupilKeys([37, 'CM', 'Cameroon', 237]),
            $this->addPupilKeys([38, 'CA', 'Canada', 1]),
            $this->addPupilKeys([39, 'CV', 'Cape Verde', 238]),
            $this->addPupilKeys([40, 'KY', 'Cayman Islands', 1345]),
            $this->addPupilKeys([41, 'CF', 'Central African Republic', 236]),
            $this->addPupilKeys([42, 'TD', 'Chad', 235]),
            $this->addPupilKeys([43, 'CL', 'Chile', 56]),
            $this->addPupilKeys([44, 'CN', 'China', 86]),
            $this->addPupilKeys([45, 'CX', 'Christmas Island', 61]),
            $this->addPupilKeys([46, 'CC', 'Cocos (Keeling) Islands', 672]),
            $this->addPupilKeys([47, 'CO', 'Colombia', 57]),
            $this->addPupilKeys([48, 'KM', 'Comoros', 269]),
            $this->addPupilKeys([49, 'CG', 'Republic Of The Congo', 242]),
            $this->addPupilKeys([50, 'CD', 'Democratic Republic Of The Congo', 242]),
            $this->addPupilKeys([51, 'CK', 'Cook Islands', 682]),
            $this->addPupilKeys([52, 'CR', 'Costa Rica', 506]),
            $this->addPupilKeys([53, 'CI', 'Cote D\'Ivoire (Ivory Coast)', 225]),
            $this->addPupilKeys([54, 'HR', 'Croatia (Hrvatska)', 385]),
            $this->addPupilKeys([55, 'CU', 'Cuba', 53]),
            $this->addPupilKeys([56, 'CY', 'Cyprus', 357]),
            $this->addPupilKeys([57, 'CZ', 'Czech Republic', 420]),
            $this->addPupilKeys([58, 'DK', 'Denmark', 45]),
            $this->addPupilKeys([59, 'DJ', 'Djibouti', 253]),
            $this->addPupilKeys([60, 'DM', 'Dominica', 1767]),
            $this->addPupilKeys([61, 'DO', 'Dominican Republic', 1809]),
            $this->addPupilKeys([62, 'TP', 'East Timor', 670]),
            $this->addPupilKeys([63, 'EC', 'Ecuador', 593]),
            $this->addPupilKeys([64, 'EG', 'Egypt', 20]),
            $this->addPupilKeys([65, 'SV', 'El Salvador', 503]),
            $this->addPupilKeys([66, 'GQ', 'Equatorial Guinea', 240]),
            $this->addPupilKeys([67, 'ER', 'Eritrea', 291]),
            $this->addPupilKeys([68, 'EE', 'Estonia', 372]),
            $this->addPupilKeys([69, 'ET', 'Ethiopia', 251]),
            $this->addPupilKeys([70, 'XA', 'External Territories of Australia', 61]),
            $this->addPupilKeys([71, 'FK', 'Falkland Islands', 500]),
            $this->addPupilKeys([72, 'FO', 'Faroe Islands', 298]),
            $this->addPupilKeys([73, 'FJ', 'Fiji Islands', 679]),
            $this->addPupilKeys([74, 'FI', 'Finland', 358]),
            $this->addPupilKeys([75, 'FR', 'France', 33]),
            $this->addPupilKeys([76, 'GF', 'French Guiana', 594]),
            $this->addPupilKeys([77, 'PF', 'French Polynesia', 689]),
            $this->addPupilKeys([78, 'TF', 'French Southern Territories', 0]),
            $this->addPupilKeys([79, 'GA', 'Gabon', 241]),
            $this->addPupilKeys([80, 'GM', 'Gambia The', 220]),
            $this->addPupilKeys([81, 'GE', 'Georgia', 995]),
            $this->addPupilKeys([82, 'DE', 'Germany', 49]),
            $this->addPupilKeys([83, 'GH', 'Ghana', 233]),
            $this->addPupilKeys([84, 'GI', 'Gibraltar', 350]),
            $this->addPupilKeys([85, 'GR', 'Greece', 30]),
            $this->addPupilKeys([86, 'GL', 'Greenland', 299]),
            $this->addPupilKeys([87, 'GD', 'Grenada', 1473]),
            $this->addPupilKeys([88, 'GP', 'Guadeloupe', 590]),
            $this->addPupilKeys([89, 'GU', 'Guam', 1671]),
            $this->addPupilKeys([90, 'GT', 'Guatemala', 502]),
            $this->addPupilKeys([91, 'XU', 'Guernsey and Alderney', 44]),
            $this->addPupilKeys([92, 'GN', 'Guinea', 224]),
            $this->addPupilKeys([93, 'GW', 'Guinea-Bissau', 245]),
            $this->addPupilKeys([94, 'GY', 'Guyana', 592]),
            $this->addPupilKeys([95, 'HT', 'Haiti', 509]),
            $this->addPupilKeys([96, 'HM', 'Heard and McDonald Islands', 0]),
            $this->addPupilKeys([97, 'HN', 'Honduras', 504]),
            $this->addPupilKeys([98, 'HK', 'Hong Kong S.A.R.', 852]),
            $this->addPupilKeys([99, 'HU', 'Hungary', 36]),
            $this->addPupilKeys([100, 'IS', 'Iceland', 354]),
            $this->addPupilKeys([101, 'IN', 'India', 91]),
            $this->addPupilKeys([102, 'ID', 'Indonesia', 62]),
            $this->addPupilKeys([103, 'IR', 'Iran', 98]),
            $this->addPupilKeys([104, 'IQ', 'Iraq', 964]),
            $this->addPupilKeys([105, 'IE', 'Ireland', 353]),
            $this->addPupilKeys([106, 'IL', 'Israel', 972]),
            $this->addPupilKeys([107, 'IT', 'Italy', 39]),
            $this->addPupilKeys([108, 'JM', 'Jamaica', 1876]),
            $this->addPupilKeys([109, 'JP', 'Japan', 81]),
            $this->addPupilKeys([110, 'XJ', 'Jersey', 44]),
            $this->addPupilKeys([111, 'JO', 'Jordan', 962]),
            $this->addPupilKeys([112, 'KZ', 'Kazakhstan', 7]),
            $this->addPupilKeys([113, 'KE', 'Kenya', 254]),
            $this->addPupilKeys([114, 'KI', 'Kiribati', 686]),
            $this->addPupilKeys([115, 'KP', 'Korea North', 850]),
            $this->addPupilKeys([116, 'KR', 'Korea South', 82]),
            $this->addPupilKeys([117, 'KW', 'Kuwait', 965]),
            $this->addPupilKeys([118, 'KG', 'Kyrgyzstan', 996]),
            $this->addPupilKeys([119, 'LA', 'Laos', 856]),
            $this->addPupilKeys([120, 'LV', 'Latvia', 371]),
            $this->addPupilKeys([121, 'LB', 'Lebanon', 961]),
            $this->addPupilKeys([122, 'LS', 'Lesotho', 266]),
            $this->addPupilKeys([123, 'LR', 'Liberia', 231]),
            $this->addPupilKeys([124, 'LY', 'Libya', 218]),
            $this->addPupilKeys([125, 'LI', 'Liechtenstein', 423]),
            $this->addPupilKeys([126, 'LT', 'Lithuania', 370]),
            $this->addPupilKeys([127, 'LU', 'Luxembourg', 352]),
            $this->addPupilKeys([128, 'MO', 'Macau S.A.R.', 853]),
            $this->addPupilKeys([129, 'MK', 'Macedonia', 389]),
            $this->addPupilKeys([130, 'MG', 'Madagascar', 261]),
            $this->addPupilKeys([131, 'MW', 'Malawi', 265]),
            $this->addPupilKeys([132, 'MY', 'Malaysia', 60]),
            $this->addPupilKeys([133, 'MV', 'Maldives', 960]),
            $this->addPupilKeys([134, 'ML', 'Mali', 223]),
            $this->addPupilKeys([135, 'MT', 'Malta', 356]),
            $this->addPupilKeys([136, 'XM', 'Isle of Man', 44]),
            $this->addPupilKeys([137, 'MH', 'Marshall Islands', 692]),
            $this->addPupilKeys([138, 'MQ', 'Martinique', 596]),
            $this->addPupilKeys([139, 'MR', 'Mauritania', 222]),
            $this->addPupilKeys([140, 'MU', 'Mauritius', 230]),
            $this->addPupilKeys([141, 'YT', 'Mayotte', 269]),
            $this->addPupilKeys([142, 'MX', 'Mexico', 52]),
            $this->addPupilKeys([143, 'FM', 'Micronesia', 691]),
            $this->addPupilKeys([144, 'MD', 'Moldova', 373]),
            $this->addPupilKeys([145, 'MC', 'Monaco', 377]),
            $this->addPupilKeys([146, 'MN', 'Mongolia', 976]),
            $this->addPupilKeys([147, 'MS', 'Montserrat', 1664]),
            $this->addPupilKeys([148, 'MA', 'Morocco', 212]),
            $this->addPupilKeys([149, 'MZ', 'Mozambique', 258]),
            $this->addPupilKeys([150, 'MM', 'Myanmar', 95]),
            $this->addPupilKeys([151, 'NA', 'Namibia', 264]),
            $this->addPupilKeys([152, 'NR', 'Nauru', 674]),
            $this->addPupilKeys([153, 'NP', 'Nepal', 977]),
            $this->addPupilKeys([154, 'AN', 'Netherlands Antilles', 599]),
            $this->addPupilKeys([155, 'NL', 'Netherlands The', 31]),
            $this->addPupilKeys([156, 'NC', 'New Caledonia', 687]),
            $this->addPupilKeys([157, 'NZ', 'New Zealand', 64]),
            $this->addPupilKeys([158, 'NI', 'Nicaragua', 505]),
            $this->addPupilKeys([159, 'NE', 'Niger', 227]),
            $this->addPupilKeys([160, 'NG', 'Nigeria', 234]),
            $this->addPupilKeys([161, 'NU', 'Niue', 683]),
            $this->addPupilKeys([162, 'NF', 'Norfolk Island', 672]),
            $this->addPupilKeys([163, 'MP', 'Northern Mariana Islands', 1670]),
            $this->addPupilKeys([164, 'NO', 'Norway', 47]),
            $this->addPupilKeys([165, 'OM', 'Oman', 968]),
            $this->addPupilKeys([166, 'PK', 'Pakistan', 92]),
            $this->addPupilKeys([167, 'PW', 'Palau', 680]),
            $this->addPupilKeys([168, 'PS', 'Palestinian Territory Occupied', 970]),
            $this->addPupilKeys([169, 'PA', 'Panama', 507]),
            $this->addPupilKeys([170, 'PG', 'Papua new Guinea', 675]),
            $this->addPupilKeys([171, 'PY', 'Paraguay', 595]),
            $this->addPupilKeys([172, 'PE', 'Peru', 51]),
            $this->addPupilKeys([173, 'PH', 'Philippines', 63]),
            $this->addPupilKeys([174, 'PN', 'Pitcairn Island', 0]),
            $this->addPupilKeys([175, 'PL', 'Poland', 48]),
            $this->addPupilKeys([176, 'PT', 'Portugal', 351]),
            $this->addPupilKeys([177, 'PR', 'Puerto Rico', 1787]),
            $this->addPupilKeys([178, 'QA', 'Qatar', 974]),
            $this->addPupilKeys([179, 'RE', 'Reunion', 262]),
            $this->addPupilKeys([180, 'RO', 'Romania', 40]),
            $this->addPupilKeys([181, 'RU', 'Russia', 70]),
            $this->addPupilKeys([182, 'RW', 'Rwanda', 250]),
            $this->addPupilKeys([183, 'SH', 'Saint Helena', 290]),
            $this->addPupilKeys([184, 'KN', 'Saint Kitts And Nevis', 1869]),
            $this->addPupilKeys([185, 'LC', 'Saint Lucia', 1758]),
            $this->addPupilKeys([186, 'PM', 'Saint Pierre and Miquelon', 508]),
            $this->addPupilKeys([187, 'VC', 'Saint Vincent And The Grenadines', 1784]),
            $this->addPupilKeys([188, 'WS', 'Samoa', 684]),
            $this->addPupilKeys([189, 'SM', 'San Marino', 378]),
            $this->addPupilKeys([190, 'ST', 'Sao Tome and Principe', 239]),
            $this->addPupilKeys([191, 'SA', 'Saudi Arabia', 966]),
            $this->addPupilKeys([192, 'SN', 'Senegal', 221]),
            $this->addPupilKeys([193, 'RS', 'Serbia', 381]),
            $this->addPupilKeys([194, 'SC', 'Seychelles', 248]),
            $this->addPupilKeys([195, 'SL', 'Sierra Leone', 232]),
            $this->addPupilKeys([196, 'SG', 'Singapore', 65]),
            $this->addPupilKeys([197, 'SK', 'Slovakia', 421]),
            $this->addPupilKeys([198, 'SI', 'Slovenia', 386]),
            $this->addPupilKeys([199, 'XG', 'Smaller Territories of the UK', 44]),
            $this->addPupilKeys([200, 'SB', 'Solomon Islands', 677]),
            $this->addPupilKeys([201, 'SO', 'Somalia', 252]),
            $this->addPupilKeys([202, 'ZA', 'South Africa', 27]),
            $this->addPupilKeys([203, 'GS', 'South Georgia', 0]),
            $this->addPupilKeys([204, 'SS', 'South Sudan', 211]),
            $this->addPupilKeys([205, 'ES', 'Spain', 34]),
            $this->addPupilKeys([206, 'LK', 'Sri Lanka', 94]),
            $this->addPupilKeys([207, 'SD', 'Sudan', 249]),
            $this->addPupilKeys([208, 'SR', 'Suriname', 597]),
            $this->addPupilKeys([209, 'SJ', 'Svalbard And Jan Mayen Islands', 47]),
            $this->addPupilKeys([210, 'SZ', 'Swaziland', 268]),
            $this->addPupilKeys([211, 'SE', 'Sweden', 46]),
            $this->addPupilKeys([212, 'CH', 'Switzerland', 41]),
            $this->addPupilKeys([213, 'SY', 'Syria', 963]),
            $this->addPupilKeys([214, 'TW', 'Taiwan', 886]),
            $this->addPupilKeys([215, 'TJ', 'Tajikistan', 992]),
            $this->addPupilKeys([216, 'TZ', 'Tanzania', 255]),
            $this->addPupilKeys([217, 'TH', 'Thailand', 66]),
            $this->addPupilKeys([218, 'TG', 'Togo', 228]),
            $this->addPupilKeys([219, 'TK', 'Tokelau', 690]),
            $this->addPupilKeys([220, 'TO', 'Tonga', 676]),
            $this->addPupilKeys([221, 'TT', 'Trinidad And Tobago', 1868]),
            $this->addPupilKeys([222, 'TN', 'Tunisia', 216]),
            $this->addPupilKeys([223, 'TR', 'Turkey', 90]),
            $this->addPupilKeys([224, 'TM', 'Turkmenistan', 7370]),
            $this->addPupilKeys([225, 'TC', 'Turks And Caicos Islands', 1649]),
            $this->addPupilKeys([226, 'TV', 'Tuvalu', 688]),
            $this->addPupilKeys([227, 'UG', 'Uganda', 256]),
            $this->addPupilKeys([228, 'UA', 'Ukraine', 380]),
            $this->addPupilKeys([229, 'AE', 'United Arab Emirates', 971]),
            $this->addPupilKeys([230, 'GB', 'United Kingdom', 44]),
            $this->addPupilKeys([231, 'US', 'United States', 1]),
            $this->addPupilKeys([232, 'UM', 'United States Minor Outlying Islands', 1]),
            $this->addPupilKeys([233, 'UY', 'Uruguay', 598]),
            $this->addPupilKeys([234, 'UZ', 'Uzbekistan', 998]),
            $this->addPupilKeys([235, 'VU', 'Vanuatu', 678]),
            $this->addPupilKeys([236, 'VA', 'Holy See (Vatican City)', 39]),
            $this->addPupilKeys([237, 'VE', 'Venezuela', 58]),
            $this->addPupilKeys([238, 'VN', 'Vietnam', 84]),
            $this->addPupilKeys([239, 'VG', 'British Virgin Islands', 1284]),
            $this->addPupilKeys([240, 'VI', 'US Virgin Islands', 1340]),
            $this->addPupilKeys([241, 'WF', 'Wallis And Futuna Islands', 681]),
            $this->addPupilKeys([242, 'EH', 'Western Sahara', 212]),
            $this->addPupilKeys([243, 'YE', 'Yemen', 967]),
            $this->addPupilKeys([244, 'YU', 'Yugoslavia', 38]),
            $this->addPupilKeys([245, 'ZM', 'Zambia', 260]),
            $this->addPupilKeys([246, 'ZW', 'Zimbabwe', 263]),
        ];

        DB::table('countries')->insert($countries);
    }
}

class StateSeeder extends Seeder
{
    public function addPupilKeys($array)
    {
        return [
            'id' => $array[0],
            'country_id' => $array[1],
            'state_name' => $array[2],
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->truncate();
        $states = [
            $this->addPupilKeys([1, 101, 'Andaman and Nicobar Islands']),
            $this->addPupilKeys([2, 101, 'Andhra Pradesh']),
            $this->addPupilKeys([3, 101, 'Arunachal Pradesh']),
            $this->addPupilKeys([4, 101, 'Assam']),
            $this->addPupilKeys([5, 101, 'Bihar']),
            $this->addPupilKeys([6, 101, 'Chandigarh']),
            $this->addPupilKeys([7, 101, 'Chhattisgarh']),
            $this->addPupilKeys([8, 101, 'Dadra and Nagar Haveli']),
            $this->addPupilKeys([9, 101, 'Daman and Diu']),
            $this->addPupilKeys([10, 101, 'Delhi']),
            $this->addPupilKeys([11, 101, 'Goa']),
            $this->addPupilKeys([12, 101, 'Gujarat']),
            $this->addPupilKeys([13, 101, 'Haryana']),
            $this->addPupilKeys([14, 101, 'Himachal Pradesh']),
            $this->addPupilKeys([15, 101, 'Jammu and Kashmir']),
            $this->addPupilKeys([16, 101, 'Jharkhand']),
            $this->addPupilKeys([17, 101, 'Karnataka']),
            $this->addPupilKeys([18, 101, 'Kenmore']),
            $this->addPupilKeys([19, 101, 'Kerala']),
            $this->addPupilKeys([20, 101, 'Lakshadweep']),
            $this->addPupilKeys([21, 101, 'Madhya Pradesh']),
            $this->addPupilKeys([22, 101, 'Maharashtra']),
            $this->addPupilKeys([23, 101, 'Manipur']),
            $this->addPupilKeys([24, 101, 'Meghalaya']),
            $this->addPupilKeys([25, 101, 'Mizoram']),
            $this->addPupilKeys([26, 101, 'Nagaland']),
            $this->addPupilKeys([27, 101, 'Narora']),
            $this->addPupilKeys([28, 101, 'Natwar']),
            $this->addPupilKeys([29, 101, 'Odisha']),
            $this->addPupilKeys([30, 101, 'Paschim Medinipur']),
            $this->addPupilKeys([31, 101, 'Pondicherry']),
            $this->addPupilKeys([32, 101, 'Punjab']),
            $this->addPupilKeys([33, 101, 'Rajasthan']),
            $this->addPupilKeys([34, 101, 'Sikkim']),
            $this->addPupilKeys([35, 101, 'Tamil Nadu']),
            $this->addPupilKeys([36, 101, 'Telangana']),
            $this->addPupilKeys([37, 101, 'Tripura']),
            $this->addPupilKeys([38, 101, 'Uttar Pradesh']),
            $this->addPupilKeys([39, 101, 'Uttarakhand']),
            $this->addPupilKeys([40, 101, 'Vaishali']),
            $this->addPupilKeys([41, 101, 'West Bengal']),
            $this->addPupilKeys([42, 1, 'Badakhshan']),
            $this->addPupilKeys([43, 1, 'Badgis']),
            $this->addPupilKeys([44, 1, 'Baglan']),
            $this->addPupilKeys([45, 1, 'Balkh']),
            $this->addPupilKeys([46, 1, 'Bamiyan']),
            $this->addPupilKeys([47, 1, 'Farah']),
            $this->addPupilKeys([48, 1, 'Faryab']),
            $this->addPupilKeys([49, 1, 'Gawr']),
            $this->addPupilKeys([50, 1, 'Gazni']),
            $this->addPupilKeys([51, 1, 'Herat']),
            $this->addPupilKeys([52, 1, 'Hilmand']),
            $this->addPupilKeys([53, 1, 'Jawzjan']),
            $this->addPupilKeys([54, 1, 'Kabul']),
            $this->addPupilKeys([55, 1, 'Kapisa']),
            $this->addPupilKeys([56, 1, 'Khawst']),
            $this->addPupilKeys([57, 1, 'Kunar']),
            $this->addPupilKeys([58, 1, 'Lagman']),
            $this->addPupilKeys([59, 1, 'Lawghar']),
            $this->addPupilKeys([60, 1, 'Nangarhar']),
            $this->addPupilKeys([61, 1, 'Nimruz']),
            $this->addPupilKeys([62, 1, 'Nuristan']),
            $this->addPupilKeys([63, 1, 'Paktika']),
            $this->addPupilKeys([64, 1, 'Paktiya']),
            $this->addPupilKeys([65, 1, 'Parwan']),
            $this->addPupilKeys([66, 1, 'Qandahar']),
            $this->addPupilKeys([67, 1, 'Qunduz']),
            $this->addPupilKeys([68, 1, 'Samangan']),
            $this->addPupilKeys([69, 1, 'Sar-e Pul']),
            $this->addPupilKeys([70, 1, 'Takhar']),
            $this->addPupilKeys([71, 1, 'Uruzgan']),
            $this->addPupilKeys([72, 1, 'Wardag']),
            $this->addPupilKeys([73, 1, 'Zabul']),
            $this->addPupilKeys([74, 2, 'Berat']),
            $this->addPupilKeys([75, 2, 'Bulqize']),
            $this->addPupilKeys([76, 2, 'Delvine']),
            $this->addPupilKeys([77, 2, 'Devoll']),
            $this->addPupilKeys([78, 2, 'Dibre']),
            $this->addPupilKeys([79, 2, 'Durres']),
            $this->addPupilKeys([80, 2, 'Elbasan']),
            $this->addPupilKeys([81, 2, 'Fier']),
            $this->addPupilKeys([82, 2, 'Gjirokaster']),
            $this->addPupilKeys([83, 2, 'Gramsh']),
            $this->addPupilKeys([84, 2, 'Has']),
            $this->addPupilKeys([85, 2, 'Kavaje']),
            $this->addPupilKeys([86, 2, 'Kolonje']),
            $this->addPupilKeys([87, 2, 'Korce']),
            $this->addPupilKeys([88, 2, 'Kruje']),
            $this->addPupilKeys([89, 2, 'Kucove']),
            $this->addPupilKeys([90, 2, 'Kukes']),
            $this->addPupilKeys([91, 2, 'Kurbin']),
            $this->addPupilKeys([92, 2, 'Lezhe']),
            $this->addPupilKeys([93, 2, 'Librazhd']),
            $this->addPupilKeys([94, 2, 'Lushnje']),
            $this->addPupilKeys([95, 2, 'Mallakaster']),
            $this->addPupilKeys([96, 2, 'Malsi e Madhe']),
            $this->addPupilKeys([97, 2, 'Mat']),
            $this->addPupilKeys([98, 2, 'Mirdite']),
            $this->addPupilKeys([99, 2, 'Peqin']),
            $this->addPupilKeys([100, 2, 'Permet']),
            $this->addPupilKeys([101, 2, 'Pogradec']),
            $this->addPupilKeys([102, 2, 'Puke']),
            $this->addPupilKeys([103, 2, 'Sarande']),
            $this->addPupilKeys([104, 2, 'Shkoder']),
            $this->addPupilKeys([105, 2, 'Skrapar']),
            $this->addPupilKeys([106, 2, 'Tepelene']),
            $this->addPupilKeys([107, 2, 'Tirane']),
            $this->addPupilKeys([108, 2, 'Tropoje']),
            $this->addPupilKeys([109, 2, 'Vlore']),
            $this->addPupilKeys([110, 3, '\'Ayn Daflah']),
            $this->addPupilKeys([111, 3, '\'Ayn Tamushanat']),
            $this->addPupilKeys([112, 3, 'Adrar']),
            $this->addPupilKeys([113, 3, 'Algiers']),
            $this->addPupilKeys([114, 3, 'Annabah']),
            $this->addPupilKeys([115, 3, 'Bashshar']),
            $this->addPupilKeys([116, 3, 'Batnah']),
            $this->addPupilKeys([117, 3, 'Bijayah']),
            $this->addPupilKeys([118, 3, 'Biskrah']),
            $this->addPupilKeys([119, 3, 'Blidah']),
            $this->addPupilKeys([120, 3, 'Buirah']),
            $this->addPupilKeys([121, 3, 'Bumardas']),
            $this->addPupilKeys([122, 3, 'Burj Bu Arririj']),
            $this->addPupilKeys([123, 3, 'Ghalizan']),
            $this->addPupilKeys([124, 3, 'Ghardayah']),
            $this->addPupilKeys([125, 3, 'Ilizi']),
            $this->addPupilKeys([126, 3, 'Jijili']),
            $this->addPupilKeys([127, 3, 'Jilfah']),
            $this->addPupilKeys([128, 3, 'Khanshalah']),
            $this->addPupilKeys([129, 3, 'Masilah']),
            $this->addPupilKeys([130, 3, 'Midyah']),
            $this->addPupilKeys([131, 3, 'Milah']),
            $this->addPupilKeys([132, 3, 'Muaskar']),
            $this->addPupilKeys([133, 3, 'Mustaghanam']),
            $this->addPupilKeys([134, 3, 'Naama']),
            $this->addPupilKeys([135, 3, 'Oran']),
            $this->addPupilKeys([136, 3, 'Ouargla']),
            $this->addPupilKeys([137, 3, 'Qalmah']),
            $this->addPupilKeys([138, 3, 'Qustantinah']),
            $this->addPupilKeys([139, 3, 'Sakikdah']),
            $this->addPupilKeys([140, 3, 'Satif']),
            $this->addPupilKeys([141, 3, 'Sayda\'']),
            $this->addPupilKeys([142, 3, 'Sidi ban-al-\'Abbas']),
            $this->addPupilKeys([143, 3, 'Suq Ahras']),
            $this->addPupilKeys([144, 3, 'Tamanghasat']),
            $this->addPupilKeys([145, 3, 'Tibazah']),
            $this->addPupilKeys([146, 3, 'Tibissah']),
            $this->addPupilKeys([147, 3, 'Tilimsan']),
            $this->addPupilKeys([148, 3, 'Tinduf']),
            $this->addPupilKeys([149, 3, 'Tisamsilt']),
            $this->addPupilKeys([150, 3, 'Tiyarat']),
            $this->addPupilKeys([151, 3, 'Tizi Wazu']),
            $this->addPupilKeys([152, 3, 'Umm-al-Bawaghi']),
            $this->addPupilKeys([153, 3, 'Wahran']),
            $this->addPupilKeys([154, 3, 'Warqla']),
            $this->addPupilKeys([155, 3, 'Wilaya d Alger']),
            $this->addPupilKeys([156, 3, 'Wilaya de Bejaia']),
            $this->addPupilKeys([157, 3, 'Wilaya de Constantine']),
            $this->addPupilKeys([158, 3, 'al-Aghwat']),
            $this->addPupilKeys([159, 3, 'al-Bayadh']),
            $this->addPupilKeys([160, 3, 'al-Jaza\'ir']),
            $this->addPupilKeys([161, 3, 'al-Wad']),
            $this->addPupilKeys([162, 3, 'ash-Shalif']),
            $this->addPupilKeys([163, 3, 'at-Tarif']),
            $this->addPupilKeys([164, 4, 'Eastern']),
            $this->addPupilKeys([165, 4, 'Manu\'a']),
            $this->addPupilKeys([166, 4, 'Swains Island']),
            $this->addPupilKeys([167, 4, 'Western']),
            $this->addPupilKeys([168, 5, 'Andorra la Vella']),
            $this->addPupilKeys([169, 5, 'Canillo']),
            $this->addPupilKeys([170, 5, 'Encamp']),
            $this->addPupilKeys([171, 5, 'La Massana']),
            $this->addPupilKeys([172, 5, 'Les Escaldes']),
            $this->addPupilKeys([173, 5, 'Ordino']),
            $this->addPupilKeys([174, 5, 'Sant Julia de Loria']),
            $this->addPupilKeys([175, 6, 'Bengo']),
            $this->addPupilKeys([176, 6, 'Benguela']),
            $this->addPupilKeys([177, 6, 'Bie']),
            $this->addPupilKeys([178, 6, 'Cabinda']),
            $this->addPupilKeys([179, 6, 'Cunene']),
            $this->addPupilKeys([180, 6, 'Huambo']),
            $this->addPupilKeys([181, 6, 'Huila']),
            $this->addPupilKeys([182, 6, 'Kuando-Kubango']),
            $this->addPupilKeys([183, 6, 'Kwanza Norte']),
            $this->addPupilKeys([184, 6, 'Kwanza Sul']),
            $this->addPupilKeys([185, 6, 'Luanda']),
            $this->addPupilKeys([186, 6, 'Lunda Norte']),
            $this->addPupilKeys([187, 6, 'Lunda Sul']),
            $this->addPupilKeys([188, 6, 'Malanje']),
            $this->addPupilKeys([189, 6, 'Moxico']),
            $this->addPupilKeys([190, 6, 'Namibe']),
            $this->addPupilKeys([191, 6, 'Uige']),
            $this->addPupilKeys([192, 6, 'Zaire']),
            $this->addPupilKeys([193, 7, 'Other Provinces']),
            $this->addPupilKeys([194, 8, 'Sector claimed by Argentina/Ch']),
            $this->addPupilKeys([195, 8, 'Sector claimed by Argentina/UK']),
            $this->addPupilKeys([196, 8, 'Sector claimed by Australia']),
            $this->addPupilKeys([197, 8, 'Sector claimed by France']),
            $this->addPupilKeys([198, 8, 'Sector claimed by New Zealand']),
            $this->addPupilKeys([199, 8, 'Sector claimed by Norway']),
            $this->addPupilKeys([200, 8, 'Unclaimed Sector']),
            $this->addPupilKeys([201, 9, 'Barbuda']),
            $this->addPupilKeys([202, 9, 'Saint George']),
            $this->addPupilKeys([203, 9, 'Saint John']),
            $this->addPupilKeys([204, 9, 'Saint Mary']),
            $this->addPupilKeys([205, 9, 'Saint Paul']),
            $this->addPupilKeys([206, 9, 'Saint Peter']),
            $this->addPupilKeys([207, 9, 'Saint Philip']),
            $this->addPupilKeys([208, 10, 'Buenos Aires']),
            $this->addPupilKeys([209, 10, 'Catamarca']),
            $this->addPupilKeys([210, 10, 'Chaco']),
            $this->addPupilKeys([211, 10, 'Chubut']),
            $this->addPupilKeys([212, 10, 'Cordoba']),
            $this->addPupilKeys([213, 10, 'Corrientes']),
            $this->addPupilKeys([214, 10, 'Distrito Federal']),
            $this->addPupilKeys([215, 10, 'Entre Rios']),
            $this->addPupilKeys([216, 10, 'Formosa']),
            $this->addPupilKeys([217, 10, 'Jujuy']),
            $this->addPupilKeys([218, 10, 'La Pampa']),
            $this->addPupilKeys([219, 10, 'La Rioja']),
            $this->addPupilKeys([220, 10, 'Mendoza']),
            $this->addPupilKeys([221, 10, 'Misiones']),
            $this->addPupilKeys([222, 10, 'Neuquen']),
            $this->addPupilKeys([223, 10, 'Rio Negro']),
            $this->addPupilKeys([224, 10, 'Salta']),
            $this->addPupilKeys([225, 10, 'San Juan']),
            $this->addPupilKeys([226, 10, 'San Luis']),
            $this->addPupilKeys([227, 10, 'Santa Cruz']),
            $this->addPupilKeys([228, 10, 'Santa Fe']),
            $this->addPupilKeys([229, 10, 'Santiago del Estero']),
            $this->addPupilKeys([230, 10, 'Tierra del Fuego']),
            $this->addPupilKeys([231, 10, 'Tucuman']),
            $this->addPupilKeys([232, 11, 'Aragatsotn']),
            $this->addPupilKeys([233, 11, 'Ararat']),
            $this->addPupilKeys([234, 11, 'Armavir']),
            $this->addPupilKeys([235, 11, 'Gegharkunik']),
            $this->addPupilKeys([236, 11, 'Kotaik']),
            $this->addPupilKeys([237, 11, 'Lori']),
            $this->addPupilKeys([238, 11, 'Shirak']),
            $this->addPupilKeys([239, 11, 'Stepanakert']),
            $this->addPupilKeys([240, 11, 'Syunik']),
            $this->addPupilKeys([241, 11, 'Tavush']),
            $this->addPupilKeys([242, 11, 'Vayots Dzor']),
            $this->addPupilKeys([243, 11, 'Yerevan']),
            $this->addPupilKeys([244, 12, 'Aruba']),
            $this->addPupilKeys([245, 13, 'Auckland']),
            $this->addPupilKeys([246, 13, 'Australian Capital Territory']),
            $this->addPupilKeys([247, 13, 'Balgowlah']),
            $this->addPupilKeys([248, 13, 'Balmain']),
            $this->addPupilKeys([249, 13, 'Bankstown']),
            $this->addPupilKeys([250, 13, 'Baulkham Hills']),
            $this->addPupilKeys([251, 13, 'Bonnet Bay']),
            $this->addPupilKeys([252, 13, 'Camberwell']),
            $this->addPupilKeys([253, 13, 'Carole Park']),
            $this->addPupilKeys([254, 13, 'Castle Hill']),
            $this->addPupilKeys([255, 13, 'Caulfield']),
            $this->addPupilKeys([256, 13, 'Chatswood']),
            $this->addPupilKeys([257, 13, 'Cheltenham']),
            $this->addPupilKeys([258, 13, 'Cherrybrook']),
            $this->addPupilKeys([259, 13, 'Clayton']),
            $this->addPupilKeys([260, 13, 'Collingwood']),
            $this->addPupilKeys([261, 13, 'Frenchs Forest']),
            $this->addPupilKeys([262, 13, 'Hawthorn']),
            $this->addPupilKeys([263, 13, 'Jannnali']),
            $this->addPupilKeys([264, 13, 'Knoxfield']),
            $this->addPupilKeys([265, 13, 'Melbourne']),
            $this->addPupilKeys([266, 13, 'New South Wales']),
            $this->addPupilKeys([267, 13, 'Northern Territory']),
            $this->addPupilKeys([268, 13, 'Perth']),
            $this->addPupilKeys([269, 13, 'Queensland']),
            $this->addPupilKeys([270, 13, 'South Australia']),
            $this->addPupilKeys([271, 13, 'Tasmania']),
            $this->addPupilKeys([272, 13, 'Templestowe']),
            $this->addPupilKeys([273, 13, 'Victoria']),
            $this->addPupilKeys([274, 13, 'Werribee south']),
            $this->addPupilKeys([275, 13, 'Western Australia']),
            $this->addPupilKeys([276, 13, 'Wheeler']),
            $this->addPupilKeys([277, 14, 'Bundesland Salzburg']),
            $this->addPupilKeys([278, 14, 'Bundesland Steiermark']),
            $this->addPupilKeys([279, 14, 'Bundesland Tirol']),
            $this->addPupilKeys([280, 14, 'Burgenland']),
            $this->addPupilKeys([281, 14, 'Carinthia']),
            $this->addPupilKeys([282, 14, 'Karnten']),
            $this->addPupilKeys([283, 14, 'Liezen']),
            $this->addPupilKeys([284, 14, 'Lower Austria']),
            $this->addPupilKeys([285, 14, 'Niederosterreich']),
            $this->addPupilKeys([286, 14, 'Oberosterreich']),
            $this->addPupilKeys([287, 14, 'Salzburg']),
            $this->addPupilKeys([288, 14, 'Schleswig-Holstein']),
            $this->addPupilKeys([289, 14, 'Steiermark']),
            $this->addPupilKeys([290, 14, 'Styria']),
            $this->addPupilKeys([291, 14, 'Tirol']),
            $this->addPupilKeys([292, 14, 'Upper Austria']),
            $this->addPupilKeys([293, 14, 'Vorarlberg']),
            $this->addPupilKeys([294, 14, 'Wien']),
            $this->addPupilKeys([295, 15, 'Abseron']),
            $this->addPupilKeys([296, 15, 'Baki Sahari']),
            $this->addPupilKeys([297, 15, 'Ganca']),
            $this->addPupilKeys([298, 15, 'Ganja']),
            $this->addPupilKeys([299, 15, 'Kalbacar']),
            $this->addPupilKeys([300, 15, 'Lankaran']),
            $this->addPupilKeys([301, 15, 'Mil-Qarabax']),
            $this->addPupilKeys([302, 15, 'Mugan-Salyan']),
            $this->addPupilKeys([303, 15, 'Nagorni-Qarabax']),
            $this->addPupilKeys([304, 15, 'Naxcivan']),
            $this->addPupilKeys([305, 15, 'Priaraks']),
            $this->addPupilKeys([306, 15, 'Qazax']),
            $this->addPupilKeys([307, 15, 'Saki']),
            $this->addPupilKeys([308, 15, 'Sirvan']),
            $this->addPupilKeys([309, 15, 'Xacmaz']),
            $this->addPupilKeys([310, 16, 'Abaco']),
            $this->addPupilKeys([311, 16, 'Acklins Island']),
            $this->addPupilKeys([312, 16, 'Andros']),
            $this->addPupilKeys([313, 16, 'Berry Islands']),
            $this->addPupilKeys([314, 16, 'Biminis']),
            $this->addPupilKeys([315, 16, 'Cat Island']),
            $this->addPupilKeys([316, 16, 'Crooked Island']),
            $this->addPupilKeys([317, 16, 'Eleuthera']),
            $this->addPupilKeys([318, 16, 'Exuma and Cays']),
            $this->addPupilKeys([319, 16, 'Grand Bahama']),
            $this->addPupilKeys([320, 16, 'Inagua Islands']),
            $this->addPupilKeys([321, 16, 'Long Island']),
            $this->addPupilKeys([322, 16, 'Mayaguana']),
            $this->addPupilKeys([323, 16, 'New Providence']),
            $this->addPupilKeys([324, 16, 'Ragged Island']),
            $this->addPupilKeys([325, 16, 'Rum Cay']),
            $this->addPupilKeys([326, 16, 'San Salvador']),
            $this->addPupilKeys([327, 17, '\'Isa']),
            $this->addPupilKeys([328, 17, 'Badiyah']),
            $this->addPupilKeys([329, 17, 'Hidd']),
            $this->addPupilKeys([330, 17, 'Jidd Hafs']),
            $this->addPupilKeys([331, 17, 'Mahama']),
            $this->addPupilKeys([332, 17, 'Manama']),
            $this->addPupilKeys([333, 17, 'Sitrah']),
            $this->addPupilKeys([334, 17, 'al-Manamah']),
            $this->addPupilKeys([335, 17, 'al-Muharraq']),
            $this->addPupilKeys([336, 17, 'ar-Rifa\'a']),
            $this->addPupilKeys([337, 18, 'Bagar Hat']),
            $this->addPupilKeys([338, 18, 'Bandarban']),
            $this->addPupilKeys([339, 18, 'Barguna']),
            $this->addPupilKeys([340, 18, 'Barisal']),
            $this->addPupilKeys([341, 18, 'Bhola']),
            $this->addPupilKeys([342, 18, 'Bogora']),
            $this->addPupilKeys([343, 18, 'Brahman Bariya']),
            $this->addPupilKeys([344, 18, 'Chandpur']),
            $this->addPupilKeys([345, 18, 'Chattagam']),
            $this->addPupilKeys([346, 18, 'Chittagong Division']),
            $this->addPupilKeys([347, 18, 'Chuadanga']),
            $this->addPupilKeys([348, 18, 'Dhaka']),
            $this->addPupilKeys([349, 18, 'Dinajpur']),
            $this->addPupilKeys([350, 18, 'Faridpur']),
            $this->addPupilKeys([351, 18, 'Feni']),
            $this->addPupilKeys([352, 18, 'Gaybanda']),
            $this->addPupilKeys([353, 18, 'Gazipur']),
            $this->addPupilKeys([354, 18, 'Gopalganj']),
            $this->addPupilKeys([355, 18, 'Habiganj']),
            $this->addPupilKeys([356, 18, 'Jaipur Hat']),
            $this->addPupilKeys([357, 18, 'Jamalpur']),
            $this->addPupilKeys([358, 18, 'Jessor']),
            $this->addPupilKeys([359, 18, 'Jhalakati']),
            $this->addPupilKeys([360, 18, 'Jhanaydah']),
            $this->addPupilKeys([361, 18, 'Khagrachhari']),
            $this->addPupilKeys([362, 18, 'Khulna']),
            $this->addPupilKeys([363, 18, 'Kishorganj']),
            $this->addPupilKeys([364, 18, 'Koks Bazar']),
            $this->addPupilKeys([365, 18, 'Komilla']),
            $this->addPupilKeys([366, 18, 'Kurigram']),
            $this->addPupilKeys([367, 18, 'Kushtiya']),
            $this->addPupilKeys([368, 18, 'Lakshmipur']),
            $this->addPupilKeys([369, 18, 'Lalmanir Hat']),
            $this->addPupilKeys([370, 18, 'Madaripur']),
            $this->addPupilKeys([371, 18, 'Magura']),
            $this->addPupilKeys([372, 18, 'Maimansingh']),
            $this->addPupilKeys([373, 18, 'Manikganj']),
            $this->addPupilKeys([374, 18, 'Maulvi Bazar']),
            $this->addPupilKeys([375, 18, 'Meherpur']),
            $this->addPupilKeys([376, 18, 'Munshiganj']),
            $this->addPupilKeys([377, 18, 'Naral']),
            $this->addPupilKeys([378, 18, 'Narayanganj']),
            $this->addPupilKeys([379, 18, 'Narsingdi']),
            $this->addPupilKeys([380, 18, 'Nator']),
            $this->addPupilKeys([381, 18, 'Naugaon']),
            $this->addPupilKeys([382, 18, 'Nawabganj']),
            $this->addPupilKeys([383, 18, 'Netrakona']),
            $this->addPupilKeys([384, 18, 'Nilphamari']),
            $this->addPupilKeys([385, 18, 'Noakhali']),
            $this->addPupilKeys([386, 18, 'Pabna']),
            $this->addPupilKeys([387, 18, 'Panchagarh']),
            $this->addPupilKeys([388, 18, 'Patuakhali']),
            $this->addPupilKeys([389, 18, 'Pirojpur']),
            $this->addPupilKeys([390, 18, 'Rajbari']),
            $this->addPupilKeys([391, 18, 'Rajshahi']),
            $this->addPupilKeys([392, 18, 'Rangamati']),
            $this->addPupilKeys([393, 18, 'Rangpur']),
            $this->addPupilKeys([394, 18, 'Satkhira']),
            $this->addPupilKeys([395, 18, 'Shariatpur']),
            $this->addPupilKeys([396, 18, 'Sherpur']),
            $this->addPupilKeys([397, 18, 'Silhat']),
            $this->addPupilKeys([398, 18, 'Sirajganj']),
            $this->addPupilKeys([399, 18, 'Sunamganj']),
            $this->addPupilKeys([400, 18, 'Tangayal']),
            $this->addPupilKeys([401, 18, 'Thakurgaon']),
            $this->addPupilKeys([402, 19, 'Christ Church']),
            $this->addPupilKeys([403, 19, 'Saint Andrew']),
            $this->addPupilKeys([404, 19, 'Saint George']),
            $this->addPupilKeys([405, 19, 'Saint James']),
            $this->addPupilKeys([406, 19, 'Saint John']),
            $this->addPupilKeys([407, 19, 'Saint Joseph']),
            $this->addPupilKeys([408, 19, 'Saint Lucy']),
            $this->addPupilKeys([409, 19, 'Saint Michael']),
            $this->addPupilKeys([410, 19, 'Saint Peter']),
            $this->addPupilKeys([411, 19, 'Saint Philip']),
            $this->addPupilKeys([412, 19, 'Saint Thomas']),
            $this->addPupilKeys([413, 20, 'Brest']),
            $this->addPupilKeys([414, 20, 'Homjel\'']),
            $this->addPupilKeys([415, 20, 'Hrodna']),
            $this->addPupilKeys([416, 20, 'Mahiljow']),
            $this->addPupilKeys([417, 20, 'Mahilyowskaya Voblasts']),
            $this->addPupilKeys([418, 20, 'Minsk']),
            $this->addPupilKeys([419, 20, 'Minskaja Voblasts\'']),
            $this->addPupilKeys([420, 20, 'Petrik']),
            $this->addPupilKeys([421, 20, 'Vicebsk']),
            $this->addPupilKeys([422, 21, 'Antwerpen']),
            $this->addPupilKeys([423, 21, 'Berchem']),
            $this->addPupilKeys([424, 21, 'Brabant']),
            $this->addPupilKeys([425, 21, 'Brabant Wallon']),
            $this->addPupilKeys([426, 21, 'Brussel']),
            $this->addPupilKeys([427, 21, 'East Flanders']),
            $this->addPupilKeys([428, 21, 'Hainaut']),
            $this->addPupilKeys([429, 21, 'Liege']),
            $this->addPupilKeys([430, 21, 'Limburg']),
            $this->addPupilKeys([431, 21, 'Luxembourg']),
            $this->addPupilKeys([432, 21, 'Namur']),
            $this->addPupilKeys([433, 21, 'Ontario']),
            $this->addPupilKeys([434, 21, 'Oost-Vlaanderen']),
            $this->addPupilKeys([435, 21, 'Provincie Brabant']),
            $this->addPupilKeys([436, 21, 'Vlaams-Brabant']),
            $this->addPupilKeys([437, 21, 'Wallonne']),
            $this->addPupilKeys([438, 21, 'West-Vlaanderen']),
            $this->addPupilKeys([439, 22, 'Belize']),
            $this->addPupilKeys([440, 22, 'Cayo']),
            $this->addPupilKeys([441, 22, 'Corozal']),
            $this->addPupilKeys([442, 22, 'Orange Walk']),
            $this->addPupilKeys([443, 22, 'Stann Creek']),
            $this->addPupilKeys([444, 22, 'Toledo']),
            $this->addPupilKeys([445, 23, 'Alibori']),
            $this->addPupilKeys([446, 23, 'Atacora']),
            $this->addPupilKeys([447, 23, 'Atlantique']),
            $this->addPupilKeys([448, 23, 'Borgou']),
            $this->addPupilKeys([449, 23, 'Collines']),
            $this->addPupilKeys([450, 23, 'Couffo']),
            $this->addPupilKeys([451, 23, 'Donga']),
            $this->addPupilKeys([452, 23, 'Littoral']),
            $this->addPupilKeys([453, 23, 'Mono']),
            $this->addPupilKeys([454, 23, 'Oueme']),
            $this->addPupilKeys([455, 23, 'Plateau']),
            $this->addPupilKeys([456, 23, 'Zou']),
            $this->addPupilKeys([457, 24, 'Hamilton']),
            $this->addPupilKeys([458, 24, 'Saint George']),
            $this->addPupilKeys([459, 25, 'Bumthang']),
            $this->addPupilKeys([460, 25, 'Chhukha']),
            $this->addPupilKeys([461, 25, 'Chirang']),
            $this->addPupilKeys([462, 25, 'Daga']),
            $this->addPupilKeys([463, 25, 'Geylegphug']),
            $this->addPupilKeys([464, 25, 'Ha']),
            $this->addPupilKeys([465, 25, 'Lhuntshi']),
            $this->addPupilKeys([466, 25, 'Mongar']),
            $this->addPupilKeys([467, 25, 'Pemagatsel']),
            $this->addPupilKeys([468, 25, 'Punakha']),
            $this->addPupilKeys([469, 25, 'Rinpung']),
            $this->addPupilKeys([470, 25, 'Samchi']),
            $this->addPupilKeys([471, 25, 'Samdrup Jongkhar']),
            $this->addPupilKeys([472, 25, 'Shemgang']),
            $this->addPupilKeys([473, 25, 'Tashigang']),
            $this->addPupilKeys([474, 25, 'Timphu']),
            $this->addPupilKeys([475, 25, 'Tongsa']),
            $this->addPupilKeys([476, 25, 'Wangdiphodrang']),
            $this->addPupilKeys([477, 26, 'Beni']),
            $this->addPupilKeys([478, 26, 'Chuquisaca']),
            $this->addPupilKeys([479, 26, 'Cochabamba']),
            $this->addPupilKeys([480, 26, 'La Paz']),
            $this->addPupilKeys([481, 26, 'Oruro']),
            $this->addPupilKeys([482, 26, 'Pando']),
            $this->addPupilKeys([483, 26, 'Potosi']),
            $this->addPupilKeys([484, 26, 'Santa Cruz']),
            $this->addPupilKeys([485, 26, 'Tarija']),
            $this->addPupilKeys([486, 27, 'Federacija Bosna i Hercegovina']),
            $this->addPupilKeys([487, 27, 'Republika Srpska']),
            $this->addPupilKeys([488, 28, 'Central Bobonong']),
            $this->addPupilKeys([489, 28, 'Central Boteti']),
            $this->addPupilKeys([490, 28, 'Central Mahalapye']),
            $this->addPupilKeys([491, 28, 'Central Serowe-Palapye']),
            $this->addPupilKeys([492, 28, 'Central Tutume']),
            $this->addPupilKeys([493, 28, 'Chobe']),
            $this->addPupilKeys([494, 28, 'Francistown']),
            $this->addPupilKeys([495, 28, 'Gaborone']),
            $this->addPupilKeys([496, 28, 'Ghanzi']),
            $this->addPupilKeys([497, 28, 'Jwaneng']),
            $this->addPupilKeys([498, 28, 'Kgalagadi North']),
            $this->addPupilKeys([499, 28, 'Kgalagadi South']),
            $this->addPupilKeys([500, 28, 'Kgatleng']),
            $this->addPupilKeys([501, 28, 'Kweneng']),
            $this->addPupilKeys([502, 28, 'Lobatse']),
            $this->addPupilKeys([503, 28, 'Ngamiland']),
            $this->addPupilKeys([504, 28, 'Ngwaketse']),
            $this->addPupilKeys([505, 28, 'North East']),
            $this->addPupilKeys([506, 28, 'Okavango']),
            $this->addPupilKeys([507, 28, 'Orapa']),
            $this->addPupilKeys([508, 28, 'Selibe Phikwe']),
            $this->addPupilKeys([509, 28, 'South East']),
            $this->addPupilKeys([510, 28, 'Sowa']),
            $this->addPupilKeys([511, 29, 'Bouvet Island']),
            $this->addPupilKeys([512, 30, 'Acre']),
            $this->addPupilKeys([513, 30, 'Alagoas']),
            $this->addPupilKeys([514, 30, 'Amapa']),
            $this->addPupilKeys([515, 30, 'Amazonas']),
            $this->addPupilKeys([516, 30, 'Bahia']),
            $this->addPupilKeys([517, 30, 'Ceara']),
            $this->addPupilKeys([518, 30, 'Distrito Federal']),
            $this->addPupilKeys([519, 30, 'Espirito Santo']),
            $this->addPupilKeys([520, 30, 'Estado de Sao Paulo']),
            $this->addPupilKeys([521, 30, 'Goias']),
            $this->addPupilKeys([522, 30, 'Maranhao']),
            $this->addPupilKeys([523, 30, 'Mato Grosso']),
            $this->addPupilKeys([524, 30, 'Mato Grosso do Sul']),
            $this->addPupilKeys([525, 30, 'Minas Gerais']),
            $this->addPupilKeys([526, 30, 'Para']),
            $this->addPupilKeys([527, 30, 'Paraiba']),
            $this->addPupilKeys([528, 30, 'Parana']),
            $this->addPupilKeys([529, 30, 'Pernambuco']),
            $this->addPupilKeys([530, 30, 'Piaui']),
            $this->addPupilKeys([531, 30, 'Rio Grande do Norte']),
            $this->addPupilKeys([532, 30, 'Rio Grande do Sul']),
            $this->addPupilKeys([533, 30, 'Rio de Janeiro']),
            $this->addPupilKeys([534, 30, 'Rondonia']),
            $this->addPupilKeys([535, 30, 'Roraima']),
            $this->addPupilKeys([536, 30, 'Santa Catarina']),
            $this->addPupilKeys([537, 30, 'Sao Paulo']),
            $this->addPupilKeys([538, 30, 'Sergipe']),
            $this->addPupilKeys([539, 30, 'Tocantins']),
            $this->addPupilKeys([540, 31, 'British Indian Ocean Territory']),
            $this->addPupilKeys([541, 32, 'Belait']),
            $this->addPupilKeys([542, 32, 'Brunei-Muara']),
            $this->addPupilKeys([543, 32, 'Temburong']),
            $this->addPupilKeys([544, 32, 'Tutong']),
            $this->addPupilKeys([545, 33, 'Blagoevgrad']),
            $this->addPupilKeys([546, 33, 'Burgas']),
            $this->addPupilKeys([547, 33, 'Dobrich']),
            $this->addPupilKeys([548, 33, 'Gabrovo']),
            $this->addPupilKeys([549, 33, 'Haskovo']),
            $this->addPupilKeys([550, 33, 'Jambol']),
            $this->addPupilKeys([551, 33, 'Kardzhali']),
            $this->addPupilKeys([552, 33, 'Kjustendil']),
            $this->addPupilKeys([553, 33, 'Lovech']),
            $this->addPupilKeys([554, 33, 'Montana']),
            $this->addPupilKeys([555, 33, 'Oblast Sofiya-Grad']),
            $this->addPupilKeys([556, 33, 'Pazardzhik']),
            $this->addPupilKeys([557, 33, 'Pernik']),
            $this->addPupilKeys([558, 33, 'Pleven']),
            $this->addPupilKeys([559, 33, 'Plovdiv']),
            $this->addPupilKeys([560, 33, 'Razgrad']),
            $this->addPupilKeys([561, 33, 'Ruse']),
            $this->addPupilKeys([562, 33, 'Shumen']),
            $this->addPupilKeys([563, 33, 'Silistra']),
            $this->addPupilKeys([564, 33, 'Sliven']),
            $this->addPupilKeys([565, 33, 'Smoljan']),
            $this->addPupilKeys([566, 33, 'Sofija grad']),
            $this->addPupilKeys([567, 33, 'Sofijska oblast']),
            $this->addPupilKeys([568, 33, 'Stara Zagora']),
            $this->addPupilKeys([569, 33, 'Targovishte']),
            $this->addPupilKeys([570, 33, 'Varna']),
            $this->addPupilKeys([571, 33, 'Veliko Tarnovo']),
            $this->addPupilKeys([572, 33, 'Vidin']),
            $this->addPupilKeys([573, 33, 'Vraca']),
            $this->addPupilKeys([574, 33, 'Yablaniza']),
            $this->addPupilKeys([575, 34, 'Bale']),
            $this->addPupilKeys([576, 34, 'Bam']),
            $this->addPupilKeys([577, 34, 'Bazega']),
            $this->addPupilKeys([578, 34, 'Bougouriba']),
            $this->addPupilKeys([579, 34, 'Boulgou']),
            $this->addPupilKeys([580, 34, 'Boulkiemde']),
            $this->addPupilKeys([581, 34, 'Comoe']),
            $this->addPupilKeys([582, 34, 'Ganzourgou']),
            $this->addPupilKeys([583, 34, 'Gnagna']),
            $this->addPupilKeys([584, 34, 'Gourma']),
            $this->addPupilKeys([585, 34, 'Houet']),
            $this->addPupilKeys([586, 34, 'Ioba']),
            $this->addPupilKeys([587, 34, 'Kadiogo']),
            $this->addPupilKeys([588, 34, 'Kenedougou']),
            $this->addPupilKeys([589, 34, 'Komandjari']),
            $this->addPupilKeys([590, 34, 'Kompienga']),
            $this->addPupilKeys([591, 34, 'Kossi']),
            $this->addPupilKeys([592, 34, 'Kouritenga']),
            $this->addPupilKeys([593, 34, 'Kourweogo']),
            $this->addPupilKeys([594, 34, 'Leraba']),
            $this->addPupilKeys([595, 34, 'Mouhoun']),
            $this->addPupilKeys([596, 34, 'Nahouri']),
            $this->addPupilKeys([597, 34, 'Namentenga']),
            $this->addPupilKeys([598, 34, 'Noumbiel']),
            $this->addPupilKeys([599, 34, 'Oubritenga']),
            $this->addPupilKeys([600, 34, 'Oudalan']),
            $this->addPupilKeys([601, 34, 'Passore']),
            $this->addPupilKeys([602, 34, 'Poni']),
            $this->addPupilKeys([603, 34, 'Sanguie']),
            $this->addPupilKeys([604, 34, 'Sanmatenga']),
            $this->addPupilKeys([605, 34, 'Seno']),
            $this->addPupilKeys([606, 34, 'Sissili']),
            $this->addPupilKeys([607, 34, 'Soum']),
            $this->addPupilKeys([608, 34, 'Sourou']),
            $this->addPupilKeys([609, 34, 'Tapoa']),
            $this->addPupilKeys([610, 34, 'Tuy']),
            $this->addPupilKeys([611, 34, 'Yatenga']),
            $this->addPupilKeys([612, 34, 'Zondoma']),
            $this->addPupilKeys([613, 34, 'Zoundweogo']),
            $this->addPupilKeys([614, 35, 'Bubanza']),
            $this->addPupilKeys([615, 35, 'Bujumbura']),
            $this->addPupilKeys([616, 35, 'Bururi']),
            $this->addPupilKeys([617, 35, 'Cankuzo']),
            $this->addPupilKeys([618, 35, 'Cibitoke']),
            $this->addPupilKeys([619, 35, 'Gitega']),
            $this->addPupilKeys([620, 35, 'Karuzi']),
            $this->addPupilKeys([621, 35, 'Kayanza']),
            $this->addPupilKeys([622, 35, 'Kirundo']),
            $this->addPupilKeys([623, 35, 'Makamba']),
            $this->addPupilKeys([624, 35, 'Muramvya']),
            $this->addPupilKeys([625, 35, 'Muyinga']),
            $this->addPupilKeys([626, 35, 'Ngozi']),
            $this->addPupilKeys([627, 35, 'Rutana']),
            $this->addPupilKeys([628, 35, 'Ruyigi']),
            $this->addPupilKeys([629, 36, 'Banteay Mean Chey']),
            $this->addPupilKeys([630, 36, 'Bat Dambang']),
            $this->addPupilKeys([631, 36, 'Kampong Cham']),
            $this->addPupilKeys([632, 36, 'Kampong Chhnang']),
            $this->addPupilKeys([633, 36, 'Kampong Spoeu']),
            $this->addPupilKeys([634, 36, 'Kampong Thum']),
            $this->addPupilKeys([635, 36, 'Kampot']),
            $this->addPupilKeys([636, 36, 'Kandal']),
            $this->addPupilKeys([637, 36, 'Kaoh Kong']),
            $this->addPupilKeys([638, 36, 'Kracheh']),
            $this->addPupilKeys([639, 36, 'Krong Kaeb']),
            $this->addPupilKeys([640, 36, 'Krong Pailin']),
            $this->addPupilKeys([641, 36, 'Krong Preah Sihanouk']),
            $this->addPupilKeys([642, 36, 'Mondol Kiri']),
            $this->addPupilKeys([643, 36, 'Otdar Mean Chey']),
            $this->addPupilKeys([644, 36, 'Phnum Penh']),
            $this->addPupilKeys([645, 36, 'Pousat']),
            $this->addPupilKeys([646, 36, 'Preah Vihear']),
            $this->addPupilKeys([647, 36, 'Prey Veaeng']),
            $this->addPupilKeys([648, 36, 'Rotanak Kiri']),
            $this->addPupilKeys([649, 36, 'Siem Reab']),
            $this->addPupilKeys([650, 36, 'Stueng Traeng']),
            $this->addPupilKeys([651, 36, 'Svay Rieng']),
            $this->addPupilKeys([652, 36, 'Takaev']),
            $this->addPupilKeys([653, 37, 'Adamaoua']),
            $this->addPupilKeys([654, 37, 'Centre']),
            $this->addPupilKeys([655, 37, 'Est']),
            $this->addPupilKeys([656, 37, 'Littoral']),
            $this->addPupilKeys([657, 37, 'Nord']),
            $this->addPupilKeys([658, 37, 'Nord Extreme']),
            $this->addPupilKeys([659, 37, 'Nordouest']),
            $this->addPupilKeys([660, 37, 'Ouest']),
            $this->addPupilKeys([661, 37, 'Sud']),
            $this->addPupilKeys([662, 37, 'Sudouest']),
            $this->addPupilKeys([663, 38, 'Alberta']),
            $this->addPupilKeys([664, 38, 'British Columbia']),
            $this->addPupilKeys([665, 38, 'Manitoba']),
            $this->addPupilKeys([666, 38, 'New Brunswick']),
            $this->addPupilKeys([667, 38, 'Newfoundland and Labrador']),
            $this->addPupilKeys([668, 38, 'Northwest Territories']),
            $this->addPupilKeys([669, 38, 'Nova Scotia']),
            $this->addPupilKeys([670, 38, 'Nunavut']),
            $this->addPupilKeys([671, 38, 'Ontario']),
            $this->addPupilKeys([672, 38, 'Prince Edward Island']),
            $this->addPupilKeys([673, 38, 'Quebec']),
            $this->addPupilKeys([674, 38, 'Saskatchewan']),
            $this->addPupilKeys([675, 38, 'Yukon']),
            $this->addPupilKeys([676, 39, 'Boavista']),
            $this->addPupilKeys([677, 39, 'Brava']),
            $this->addPupilKeys([678, 39, 'Fogo']),
            $this->addPupilKeys([679, 39, 'Maio']),
            $this->addPupilKeys([680, 39, 'Sal']),
            $this->addPupilKeys([681, 39, 'Santo Antao']),
            $this->addPupilKeys([682, 39, 'Sao Nicolau']),
            $this->addPupilKeys([683, 39, 'Sao Tiago']),
            $this->addPupilKeys([684, 39, 'Sao Vicente']),
            $this->addPupilKeys([685, 40, 'Grand Cayman']),
            $this->addPupilKeys([686, 41, 'Bamingui-Bangoran']),
            $this->addPupilKeys([687, 41, 'Bangui']),
            $this->addPupilKeys([688, 41, 'Basse-Kotto']),
            $this->addPupilKeys([689, 41, 'Haut-Mbomou']),
            $this->addPupilKeys([690, 41, 'Haute-Kotto']),
            $this->addPupilKeys([691, 41, 'Kemo']),
            $this->addPupilKeys([692, 41, 'Lobaye']),
            $this->addPupilKeys([693, 41, 'Mambere-Kadei']),
            $this->addPupilKeys([694, 41, 'Mbomou']),
            $this->addPupilKeys([695, 41, 'Nana-Gribizi']),
            $this->addPupilKeys([696, 41, 'Nana-Mambere']),
            $this->addPupilKeys([697, 41, 'Ombella Mpoko']),
            $this->addPupilKeys([698, 41, 'Ouaka']),
            $this->addPupilKeys([699, 41, 'Ouham']),
            $this->addPupilKeys([700, 41, 'Ouham-Pende']),
            $this->addPupilKeys([701, 41, 'Sangha-Mbaere']),
            $this->addPupilKeys([702, 41, 'Vakaga']),
            $this->addPupilKeys([703, 42, 'Batha']),
            $this->addPupilKeys([704, 42, 'Biltine']),
            $this->addPupilKeys([705, 42, 'Bourkou-Ennedi-Tibesti']),
            $this->addPupilKeys([706, 42, 'Chari-Baguirmi']),
            $this->addPupilKeys([707, 42, 'Guera']),
            $this->addPupilKeys([708, 42, 'Kanem']),
            $this->addPupilKeys([709, 42, 'Lac']),
            $this->addPupilKeys([710, 42, 'Logone Occidental']),
            $this->addPupilKeys([711, 42, 'Logone Oriental']),
            $this->addPupilKeys([712, 42, 'Mayo-Kebbi']),
            $this->addPupilKeys([713, 42, 'Moyen-Chari']),
            $this->addPupilKeys([714, 42, 'Ouaddai']),
            $this->addPupilKeys([715, 42, 'Salamat']),
            $this->addPupilKeys([716, 42, 'Tandjile']),
            $this->addPupilKeys([717, 43, 'Aisen']),
            $this->addPupilKeys([718, 43, 'Antofagasta']),
            $this->addPupilKeys([719, 43, 'Araucania']),
            $this->addPupilKeys([720, 43, 'Atacama']),
            $this->addPupilKeys([721, 43, 'Bio Bio']),
            $this->addPupilKeys([722, 43, 'Coquimbo']),
            $this->addPupilKeys([723, 43, 'Libertador General Bernardo O\'']),
            $this->addPupilKeys([724, 43, 'Los Lagos']),
            $this->addPupilKeys([725, 43, 'Magellanes']),
            $this->addPupilKeys([726, 43, 'Maule']),
            $this->addPupilKeys([727, 43, 'Metropolitana']),
            $this->addPupilKeys([728, 43, 'Metropolitana de Santiago']),
            $this->addPupilKeys([729, 43, 'Tarapaca']),
            $this->addPupilKeys([730, 43, 'Valparaiso']),
            $this->addPupilKeys([731, 44, 'Anhui']),
            $this->addPupilKeys([732, 44, 'Anhui Province']),
            $this->addPupilKeys([733, 44, 'Anhui Sheng']),
            $this->addPupilKeys([734, 44, 'Aomen']),
            $this->addPupilKeys([735, 44, 'Beijing']),
            $this->addPupilKeys([736, 44, 'Beijing Shi']),
            $this->addPupilKeys([737, 44, 'Chongqing']),
            $this->addPupilKeys([738, 44, 'Fujian']),
            $this->addPupilKeys([739, 44, 'Fujian Sheng']),
            $this->addPupilKeys([740, 44, 'Gansu']),
            $this->addPupilKeys([741, 44, 'Guangdong']),
            $this->addPupilKeys([742, 44, 'Guangdong Sheng']),
            $this->addPupilKeys([743, 44, 'Guangxi']),
            $this->addPupilKeys([744, 44, 'Guizhou']),
            $this->addPupilKeys([745, 44, 'Hainan']),
            $this->addPupilKeys([746, 44, 'Hebei']),
            $this->addPupilKeys([747, 44, 'Heilongjiang']),
            $this->addPupilKeys([748, 44, 'Henan']),
            $this->addPupilKeys([749, 44, 'Hubei']),
            $this->addPupilKeys([750, 44, 'Hunan']),
            $this->addPupilKeys([751, 44, 'Jiangsu']),
            $this->addPupilKeys([752, 44, 'Jiangsu Sheng']),
            $this->addPupilKeys([753, 44, 'Jiangxi']),
            $this->addPupilKeys([754, 44, 'Jilin']),
            $this->addPupilKeys([755, 44, 'Liaoning']),
            $this->addPupilKeys([756, 44, 'Liaoning Sheng']),
            $this->addPupilKeys([757, 44, 'Nei Monggol']),
            $this->addPupilKeys([758, 44, 'Ningxia Hui']),
            $this->addPupilKeys([759, 44, 'Qinghai']),
            $this->addPupilKeys([760, 44, 'Shaanxi']),
            $this->addPupilKeys([761, 44, 'Shandong']),
            $this->addPupilKeys([762, 44, 'Shandong Sheng']),
            $this->addPupilKeys([763, 44, 'Shanghai']),
            $this->addPupilKeys([764, 44, 'Shanxi']),
            $this->addPupilKeys([765, 44, 'Sichuan']),
            $this->addPupilKeys([766, 44, 'Tianjin']),
            $this->addPupilKeys([767, 44, 'Xianggang']),
            $this->addPupilKeys([768, 44, 'Xinjiang']),
            $this->addPupilKeys([769, 44, 'Xizang']),
            $this->addPupilKeys([770, 44, 'Yunnan']),
            $this->addPupilKeys([771, 44, 'Zhejiang']),
            $this->addPupilKeys([772, 44, 'Zhejiang Sheng']),
            $this->addPupilKeys([773, 45, 'Christmas Island']),
            $this->addPupilKeys([774, 46, 'Cocos $this->addPupilKeys([Keeling]) Islands']),
            $this->addPupilKeys([775, 47, 'Amazonas']),
            $this->addPupilKeys([776, 47, 'Antioquia']),
            $this->addPupilKeys([777, 47, 'Arauca']),
            $this->addPupilKeys([778, 47, 'Atlantico']),
            $this->addPupilKeys([779, 47, 'Bogota']),
            $this->addPupilKeys([780, 47, 'Bolivar']),
            $this->addPupilKeys([781, 47, 'Boyaca']),
            $this->addPupilKeys([782, 47, 'Caldas']),
            $this->addPupilKeys([783, 47, 'Caqueta']),
            $this->addPupilKeys([784, 47, 'Casanare']),
            $this->addPupilKeys([785, 47, 'Cauca']),
            $this->addPupilKeys([786, 47, 'Cesar']),
            $this->addPupilKeys([787, 47, 'Choco']),
            $this->addPupilKeys([788, 47, 'Cordoba']),
            $this->addPupilKeys([789, 47, 'Cundinamarca']),
            $this->addPupilKeys([790, 47, 'Guainia']),
            $this->addPupilKeys([791, 47, 'Guaviare']),
            $this->addPupilKeys([792, 47, 'Huila']),
            $this->addPupilKeys([793, 47, 'La Guajira']),
            $this->addPupilKeys([794, 47, 'Magdalena']),
            $this->addPupilKeys([795, 47, 'Meta']),
            $this->addPupilKeys([796, 47, 'Narino']),
            $this->addPupilKeys([797, 47, 'Norte de Santander']),
            $this->addPupilKeys([798, 47, 'Putumayo']),
            $this->addPupilKeys([799, 47, 'Quindio']),
            $this->addPupilKeys([800, 47, 'Risaralda']),
            $this->addPupilKeys([801, 47, 'San Andres y Providencia']),
            $this->addPupilKeys([802, 47, 'Santander']),
            $this->addPupilKeys([803, 47, 'Sucre']),
            $this->addPupilKeys([804, 47, 'Tolima']),
            $this->addPupilKeys([805, 47, 'Valle del Cauca']),
            $this->addPupilKeys([806, 47, 'Vaupes']),
            $this->addPupilKeys([807, 47, 'Vichada']),
            $this->addPupilKeys([808, 48, 'Mwali']),
            $this->addPupilKeys([809, 48, 'Njazidja']),
            $this->addPupilKeys([810, 48, 'Nzwani']),
            $this->addPupilKeys([811, 49, 'Bouenza']),
            $this->addPupilKeys([812, 49, 'Brazzaville']),
            $this->addPupilKeys([813, 49, 'Cuvette']),
            $this->addPupilKeys([814, 49, 'Kouilou']),
            $this->addPupilKeys([815, 49, 'Lekoumou']),
            $this->addPupilKeys([816, 49, 'Likouala']),
            $this->addPupilKeys([817, 49, 'Niari']),
            $this->addPupilKeys([818, 49, 'Plateaux']),
            $this->addPupilKeys([819, 49, 'Pool']),
            $this->addPupilKeys([820, 49, 'Sangha']),
            $this->addPupilKeys([821, 50, 'Bandundu']),
            $this->addPupilKeys([822, 50, 'Bas-Congo']),
            $this->addPupilKeys([823, 50, 'Equateur']),
            $this->addPupilKeys([824, 50, 'Haut-Congo']),
            $this->addPupilKeys([825, 50, 'Kasai-Occidental']),
            $this->addPupilKeys([826, 50, 'Kasai-Oriental']),
            $this->addPupilKeys([827, 50, 'Katanga']),
            $this->addPupilKeys([828, 50, 'Kinshasa']),
            $this->addPupilKeys([829, 50, 'Maniema']),
            $this->addPupilKeys([830, 50, 'Nord-Kivu']),
            $this->addPupilKeys([831, 50, 'Sud-Kivu']),
            $this->addPupilKeys([832, 51, 'Aitutaki']),
            $this->addPupilKeys([833, 51, 'Atiu']),
            $this->addPupilKeys([834, 51, 'Mangaia']),
            $this->addPupilKeys([835, 51, 'Manihiki']),
            $this->addPupilKeys([836, 51, 'Mauke']),
            $this->addPupilKeys([837, 51, 'Mitiaro']),
            $this->addPupilKeys([838, 51, 'Nassau']),
            $this->addPupilKeys([839, 51, 'Pukapuka']),
            $this->addPupilKeys([840, 51, 'Rakahanga']),
            $this->addPupilKeys([841, 51, 'Rarotonga']),
            $this->addPupilKeys([842, 51, 'Tongareva']),
            $this->addPupilKeys([843, 52, 'Alajuela']),
            $this->addPupilKeys([844, 52, 'Cartago']),
            $this->addPupilKeys([845, 52, 'Guanacaste']),
            $this->addPupilKeys([846, 52, 'Heredia']),
            $this->addPupilKeys([847, 52, 'Limon']),
            $this->addPupilKeys([848, 52, 'Puntarenas']),
            $this->addPupilKeys([849, 52, 'San Jose']),
            $this->addPupilKeys([850, 53, 'Abidjan']),
            $this->addPupilKeys([851, 53, 'Agneby']),
            $this->addPupilKeys([852, 53, 'Bafing']),
            $this->addPupilKeys([853, 53, 'Denguele']),
            $this->addPupilKeys([854, 53, 'Dix-huit Montagnes']),
            $this->addPupilKeys([855, 53, 'Fromager']),
            $this->addPupilKeys([856, 53, 'Haut-Sassandra']),
            $this->addPupilKeys([857, 53, 'Lacs']),
            $this->addPupilKeys([858, 53, 'Lagunes']),
            $this->addPupilKeys([859, 53, 'Marahoue']),
            $this->addPupilKeys([860, 53, 'Moyen-Cavally']),
            $this->addPupilKeys([861, 53, 'Moyen-Comoe']),
            $this->addPupilKeys([862, 53, 'N\'zi-Comoe']),
            $this->addPupilKeys([863, 53, 'Sassandra']),
            $this->addPupilKeys([864, 53, 'Savanes']),
            $this->addPupilKeys([865, 53, 'Sud-Bandama']),
            $this->addPupilKeys([866, 53, 'Sud-Comoe']),
            $this->addPupilKeys([867, 53, 'Vallee du Bandama']),
            $this->addPupilKeys([868, 53, 'Worodougou']),
            $this->addPupilKeys([869, 53, 'Zanzan']),
            $this->addPupilKeys([870, 54, 'Bjelovar-Bilogora']),
            $this->addPupilKeys([871, 54, 'Dubrovnik-Neretva']),
            $this->addPupilKeys([872, 54, 'Grad Zagreb']),
            $this->addPupilKeys([873, 54, 'Istra']),
            $this->addPupilKeys([874, 54, 'Karlovac']),
            $this->addPupilKeys([875, 54, 'Koprivnica-Krizhevci']),
            $this->addPupilKeys([876, 54, 'Krapina-Zagorje']),
            $this->addPupilKeys([877, 54, 'Lika-Senj']),
            $this->addPupilKeys([878, 54, 'Medhimurje']),
            $this->addPupilKeys([879, 54, 'Medimurska Zupanija']),
            $this->addPupilKeys([880, 54, 'Osijek-Baranja']),
            $this->addPupilKeys([881, 54, 'Osjecko-Baranjska Zupanija']),
            $this->addPupilKeys([882, 54, 'Pozhega-Slavonija']),
            $this->addPupilKeys([883, 54, 'Primorje-Gorski Kotar']),
            $this->addPupilKeys([884, 54, 'Shibenik-Knin']),
            $this->addPupilKeys([885, 54, 'Sisak-Moslavina']),
            $this->addPupilKeys([886, 54, 'Slavonski Brod-Posavina']),
            $this->addPupilKeys([887, 54, 'Split-Dalmacija']),
            $this->addPupilKeys([888, 54, 'Varazhdin']),
            $this->addPupilKeys([889, 54, 'Virovitica-Podravina']),
            $this->addPupilKeys([890, 54, 'Vukovar-Srijem']),
            $this->addPupilKeys([891, 54, 'Zadar']),
            $this->addPupilKeys([892, 54, 'Zagreb']),
            $this->addPupilKeys([893, 55, 'Camaguey']),
            $this->addPupilKeys([894, 55, 'Ciego de Avila']),
            $this->addPupilKeys([895, 55, 'Cienfuegos']),
            $this->addPupilKeys([896, 55, 'Ciudad de la Habana']),
            $this->addPupilKeys([897, 55, 'Granma']),
            $this->addPupilKeys([898, 55, 'Guantanamo']),
            $this->addPupilKeys([899, 55, 'Habana']),
            $this->addPupilKeys([900, 55, 'Holguin']),
            $this->addPupilKeys([901, 55, 'Isla de la Juventud']),
            $this->addPupilKeys([902, 55, 'La Habana']),
            $this->addPupilKeys([903, 55, 'Las Tunas']),
            $this->addPupilKeys([904, 55, 'Matanzas']),
            $this->addPupilKeys([905, 55, 'Pinar del Rio']),
            $this->addPupilKeys([906, 55, 'Sancti Spiritus']),
            $this->addPupilKeys([907, 55, 'Santiago de Cuba']),
            $this->addPupilKeys([908, 55, 'Villa Clara']),
            $this->addPupilKeys([909, 56, 'Government controlled area']),
            $this->addPupilKeys([910, 56, 'Limassol']),
            $this->addPupilKeys([911, 56, 'Nicosia District']),
            $this->addPupilKeys([912, 56, 'Paphos']),
            $this->addPupilKeys([913, 56, 'Turkish controlled area']),
            $this->addPupilKeys([914, 57, 'Central Bohemian']),
            $this->addPupilKeys([915, 57, 'Frycovice']),
            $this->addPupilKeys([916, 57, 'Jihocesky Kraj']),
            $this->addPupilKeys([917, 57, 'Jihochesky']),
            $this->addPupilKeys([918, 57, 'Jihomoravsky']),
            $this->addPupilKeys([919, 57, 'Karlovarsky']),
            $this->addPupilKeys([920, 57, 'Klecany']),
            $this->addPupilKeys([921, 57, 'Kralovehradecky']),
            $this->addPupilKeys([922, 57, 'Liberecky']),
            $this->addPupilKeys([923, 57, 'Lipov']),
            $this->addPupilKeys([924, 57, 'Moravskoslezsky']),
            $this->addPupilKeys([925, 57, 'Olomoucky']),
            $this->addPupilKeys([926, 57, 'Olomoucky Kraj']),
            $this->addPupilKeys([927, 57, 'Pardubicky']),
            $this->addPupilKeys([928, 57, 'Plzensky']),
            $this->addPupilKeys([929, 57, 'Praha']),
            $this->addPupilKeys([930, 57, 'Rajhrad']),
            $this->addPupilKeys([931, 57, 'Smirice']),
            $this->addPupilKeys([932, 57, 'South Moravian']),
            $this->addPupilKeys([933, 57, 'Straz nad Nisou']),
            $this->addPupilKeys([934, 57, 'Stredochesky']),
            $this->addPupilKeys([935, 57, 'Unicov']),
            $this->addPupilKeys([936, 57, 'Ustecky']),
            $this->addPupilKeys([937, 57, 'Valletta']),
            $this->addPupilKeys([938, 57, 'Velesin']),
            $this->addPupilKeys([939, 57, 'Vysochina']),
            $this->addPupilKeys([940, 57, 'Zlinsky']),
            $this->addPupilKeys([941, 58, 'Arhus']),
            $this->addPupilKeys([942, 58, 'Bornholm']),
            $this->addPupilKeys([943, 58, 'Frederiksborg']),
            $this->addPupilKeys([944, 58, 'Fyn']),
            $this->addPupilKeys([945, 58, 'Hovedstaden']),
            $this->addPupilKeys([946, 58, 'Kobenhavn']),
            $this->addPupilKeys([947, 58, 'Kobenhavns Amt']),
            $this->addPupilKeys([948, 58, 'Kobenhavns Kommune']),
            $this->addPupilKeys([949, 58, 'Nordjylland']),
            $this->addPupilKeys([950, 58, 'Ribe']),
            $this->addPupilKeys([951, 58, 'Ringkobing']),
            $this->addPupilKeys([952, 58, 'Roervig']),
            $this->addPupilKeys([953, 58, 'Roskilde']),
            $this->addPupilKeys([954, 58, 'Roslev']),
            $this->addPupilKeys([955, 58, 'Sjaelland']),
            $this->addPupilKeys([956, 58, 'Soeborg']),
            $this->addPupilKeys([957, 58, 'Sonderjylland']),
            $this->addPupilKeys([958, 58, 'Storstrom']),
            $this->addPupilKeys([959, 58, 'Syddanmark']),
            $this->addPupilKeys([960, 58, 'Toelloese']),
            $this->addPupilKeys([961, 58, 'Vejle']),
            $this->addPupilKeys([962, 58, 'Vestsjalland']),
            $this->addPupilKeys([963, 58, 'Viborg']),
            $this->addPupilKeys([964, 59, '\'Ali Sabih']),
            $this->addPupilKeys([965, 59, 'Dikhil']),
            $this->addPupilKeys([966, 59, 'Jibuti']),
            $this->addPupilKeys([967, 59, 'Tajurah']),
            $this->addPupilKeys([968, 59, 'Ubuk']),
            $this->addPupilKeys([969, 60, 'Saint Andrew']),
            $this->addPupilKeys([970, 60, 'Saint David']),
            $this->addPupilKeys([971, 60, 'Saint George']),
            $this->addPupilKeys([972, 60, 'Saint John']),
            $this->addPupilKeys([973, 60, 'Saint Joseph']),
            $this->addPupilKeys([974, 60, 'Saint Luke']),
            $this->addPupilKeys([975, 60, 'Saint Mark']),
            $this->addPupilKeys([976, 60, 'Saint Patrick']),
            $this->addPupilKeys([977, 60, 'Saint Paul']),
            $this->addPupilKeys([978, 60, 'Saint Peter']),
            $this->addPupilKeys([979, 61, 'Azua']),
            $this->addPupilKeys([980, 61, 'Bahoruco']),
            $this->addPupilKeys([981, 61, 'Barahona']),
            $this->addPupilKeys([982, 61, 'Dajabon']),
            $this->addPupilKeys([983, 61, 'Distrito Nacional']),
            $this->addPupilKeys([984, 61, 'Duarte']),
            $this->addPupilKeys([985, 61, 'El Seybo']),
            $this->addPupilKeys([986, 61, 'Elias Pina']),
            $this->addPupilKeys([987, 61, 'Espaillat']),
            $this->addPupilKeys([988, 61, 'Hato Mayor']),
            $this->addPupilKeys([989, 61, 'Independencia']),
            $this->addPupilKeys([990, 61, 'La Altagracia']),
            $this->addPupilKeys([991, 61, 'La Romana']),
            $this->addPupilKeys([992, 61, 'La Vega']),
            $this->addPupilKeys([993, 61, 'Maria Trinidad Sanchez']),
            $this->addPupilKeys([994, 61, 'Monsenor Nouel']),
            $this->addPupilKeys([995, 61, 'Monte Cristi']),
            $this->addPupilKeys([996, 61, 'Monte Plata']),
            $this->addPupilKeys([997, 61, 'Pedernales']),
            $this->addPupilKeys([998, 61, 'Peravia']),
            $this->addPupilKeys([999, 61, 'Puerto Plata']),
            $this->addPupilKeys([1000, 61, 'Salcedo']),
            $this->addPupilKeys([1001, 61, 'Samana']),
            $this->addPupilKeys([1002, 61, 'San Cristobal']),
            $this->addPupilKeys([1003, 61, 'San Juan']),
            $this->addPupilKeys([1004, 61, 'San Pedro de Macoris']),
            $this->addPupilKeys([1005, 61, 'Sanchez Ramirez']),
            $this->addPupilKeys([1006, 61, 'Santiago']),
            $this->addPupilKeys([1007, 61, 'Santiago Rodriguez']),
            $this->addPupilKeys([1008, 61, 'Valverde']),
            $this->addPupilKeys([1009, 62, 'Aileu']),
            $this->addPupilKeys([1010, 62, 'Ainaro']),
            $this->addPupilKeys([1011, 62, 'Ambeno']),
            $this->addPupilKeys([1012, 62, 'Baucau']),
            $this->addPupilKeys([1013, 62, 'Bobonaro']),
            $this->addPupilKeys([1014, 62, 'Cova Lima']),
            $this->addPupilKeys([1015, 62, 'Dili']),
            $this->addPupilKeys([1016, 62, 'Ermera']),
            $this->addPupilKeys([1017, 62, 'Lautem']),
            $this->addPupilKeys([1018, 62, 'Liquica']),
            $this->addPupilKeys([1019, 62, 'Manatuto']),
            $this->addPupilKeys([1020, 62, 'Manufahi']),
            $this->addPupilKeys([1021, 62, 'Viqueque']),
            $this->addPupilKeys([1022, 63, 'Azuay']),
            $this->addPupilKeys([1023, 63, 'Bolivar']),
            $this->addPupilKeys([1024, 63, 'Canar']),
            $this->addPupilKeys([1025, 63, 'Carchi']),
            $this->addPupilKeys([1026, 63, 'Chimborazo']),
            $this->addPupilKeys([1027, 63, 'Cotopaxi']),
            $this->addPupilKeys([1028, 63, 'El Oro']),
            $this->addPupilKeys([1029, 63, 'Esmeraldas']),
            $this->addPupilKeys([1030, 63, 'Galapagos']),
            $this->addPupilKeys([1031, 63, 'Guayas']),
            $this->addPupilKeys([1032, 63, 'Imbabura']),
            $this->addPupilKeys([1033, 63, 'Loja']),
            $this->addPupilKeys([1034, 63, 'Los Rios']),
            $this->addPupilKeys([1035, 63, 'Manabi']),
            $this->addPupilKeys([1036, 63, 'Morona Santiago']),
            $this->addPupilKeys([1037, 63, 'Napo']),
            $this->addPupilKeys([1038, 63, 'Orellana']),
            $this->addPupilKeys([1039, 63, 'Pastaza']),
            $this->addPupilKeys([1040, 63, 'Pichincha']),
            $this->addPupilKeys([1041, 63, 'Sucumbios']),
            $this->addPupilKeys([1042, 63, 'Tungurahua']),
            $this->addPupilKeys([1043, 63, 'Zamora Chinchipe']),
            $this->addPupilKeys([1044, 64, 'Aswan']),
            $this->addPupilKeys([1045, 64, 'Asyut']),
            $this->addPupilKeys([1046, 64, 'Bani Suwayf']),
            $this->addPupilKeys([1047, 64, 'Bur Sa\'id']),
            $this->addPupilKeys([1048, 64, 'Cairo']),
            $this->addPupilKeys([1049, 64, 'Dumyat']),
            $this->addPupilKeys([1050, 64, 'Kafr-ash-Shaykh']),
            $this->addPupilKeys([1051, 64, 'Matruh']),
            $this->addPupilKeys([1052, 64, 'Muhafazat ad Daqahliyah']),
            $this->addPupilKeys([1053, 64, 'Muhafazat al Fayyum']),
            $this->addPupilKeys([1054, 64, 'Muhafazat al Gharbiyah']),
            $this->addPupilKeys([1055, 64, 'Muhafazat al Iskandariyah']),
            $this->addPupilKeys([1056, 64, 'Muhafazat al Qahirah']),
            $this->addPupilKeys([1057, 64, 'Qina']),
            $this->addPupilKeys([1058, 64, 'Sawhaj']),
            $this->addPupilKeys([1059, 64, 'Sina al-Janubiyah']),
            $this->addPupilKeys([1060, 64, 'Sina ash-Shamaliyah']),
            $this->addPupilKeys([1061, 64, 'ad-Daqahliyah']),
            $this->addPupilKeys([1062, 64, 'al-Bahr-al-Ahmar']),
            $this->addPupilKeys([1063, 64, 'al-Buhayrah']),
            $this->addPupilKeys([1064, 64, 'al-Fayyum']),
            $this->addPupilKeys([1065, 64, 'al-Gharbiyah']),
            $this->addPupilKeys([1066, 64, 'al-Iskandariyah']),
            $this->addPupilKeys([1067, 64, 'al-Ismailiyah']),
            $this->addPupilKeys([1068, 64, 'al-Jizah']),
            $this->addPupilKeys([1069, 64, 'al-Minufiyah']),
            $this->addPupilKeys([1070, 64, 'al-Minya']),
            $this->addPupilKeys([1071, 64, 'al-Qahira']),
            $this->addPupilKeys([1072, 64, 'al-Qalyubiyah']),
            $this->addPupilKeys([1073, 64, 'al-Uqsur']),
            $this->addPupilKeys([1074, 64, 'al-Wadi al-Jadid']),
            $this->addPupilKeys([1075, 64, 'as-Suways']),
            $this->addPupilKeys([1076, 64, 'ash-Sharqiyah']),
            $this->addPupilKeys([1077, 65, 'Ahuachapan']),
            $this->addPupilKeys([1078, 65, 'Cabanas']),
            $this->addPupilKeys([1079, 65, 'Chalatenango']),
            $this->addPupilKeys([1080, 65, 'Cuscatlan']),
            $this->addPupilKeys([1081, 65, 'La Libertad']),
            $this->addPupilKeys([1082, 65, 'La Paz']),
            $this->addPupilKeys([1083, 65, 'La Union']),
            $this->addPupilKeys([1084, 65, 'Morazan']),
            $this->addPupilKeys([1085, 65, 'San Miguel']),
            $this->addPupilKeys([1086, 65, 'San Salvador']),
            $this->addPupilKeys([1087, 65, 'San Vicente']),
            $this->addPupilKeys([1088, 65, 'Santa Ana']),
            $this->addPupilKeys([1089, 65, 'Sonsonate']),
            $this->addPupilKeys([1090, 65, 'Usulutan']),
            $this->addPupilKeys([1091, 66, 'Annobon']),
            $this->addPupilKeys([1092, 66, 'Bioko Norte']),
            $this->addPupilKeys([1093, 66, 'Bioko Sur']),
            $this->addPupilKeys([1094, 66, 'Centro Sur']),
            $this->addPupilKeys([1095, 66, 'Kie-Ntem']),
            $this->addPupilKeys([1096, 66, 'Litoral']),
            $this->addPupilKeys([1097, 66, 'Wele-Nzas']),
            $this->addPupilKeys([1098, 67, 'Anseba']),
            $this->addPupilKeys([1099, 67, 'Debub']),
            $this->addPupilKeys([1100, 67, 'Debub-Keih-Bahri']),
            $this->addPupilKeys([1101, 67, 'Gash-Barka']),
            $this->addPupilKeys([1102, 67, 'Maekel']),
            $this->addPupilKeys([1103, 67, 'Semien-Keih-Bahri']),
            $this->addPupilKeys([1104, 68, 'Harju']),
            $this->addPupilKeys([1105, 68, 'Hiiu']),
            $this->addPupilKeys([1106, 68, 'Ida-Viru']),
            $this->addPupilKeys([1107, 68, 'Jarva']),
            $this->addPupilKeys([1108, 68, 'Jogeva']),
            $this->addPupilKeys([1109, 68, 'Laane']),
            $this->addPupilKeys([1110, 68, 'Laane-Viru']),
            $this->addPupilKeys([1111, 68, 'Parnu']),
            $this->addPupilKeys([1112, 68, 'Polva']),
            $this->addPupilKeys([1113, 68, 'Rapla']),
            $this->addPupilKeys([1114, 68, 'Saare']),
            $this->addPupilKeys([1115, 68, 'Tartu']),
            $this->addPupilKeys([1116, 68, 'Valga']),
            $this->addPupilKeys([1117, 68, 'Viljandi']),
            $this->addPupilKeys([1118, 68, 'Voru']),
            $this->addPupilKeys([1119, 69, 'Addis Abeba']),
            $this->addPupilKeys([1120, 69, 'Afar']),
            $this->addPupilKeys([1121, 69, 'Amhara']),
            $this->addPupilKeys([1122, 69, 'Benishangul']),
            $this->addPupilKeys([1123, 69, 'Diredawa']),
            $this->addPupilKeys([1124, 69, 'Gambella']),
            $this->addPupilKeys([1125, 69, 'Harar']),
            $this->addPupilKeys([1126, 69, 'Jigjiga']),
            $this->addPupilKeys([1127, 69, 'Mekele']),
            $this->addPupilKeys([1128, 69, 'Oromia']),
            $this->addPupilKeys([1129, 69, 'Somali']),
            $this->addPupilKeys([1130, 69, 'Southern']),
            $this->addPupilKeys([1131, 69, 'Tigray']),
            $this->addPupilKeys([1132, 70, 'Christmas Island']),
            $this->addPupilKeys([1133, 70, 'Cocos Islands']),
            $this->addPupilKeys([1134, 70, 'Coral Sea Islands']),
            $this->addPupilKeys([1135, 71, 'Falkland Islands']),
            $this->addPupilKeys([1136, 71, 'South Georgia']),
            $this->addPupilKeys([1137, 72, 'Klaksvik']),
            $this->addPupilKeys([1138, 72, 'Nor ara Eysturoy']),
            $this->addPupilKeys([1139, 72, 'Nor oy']),
            $this->addPupilKeys([1140, 72, 'Sandoy']),
            $this->addPupilKeys([1141, 72, 'Streymoy']),
            $this->addPupilKeys([1142, 72, 'Su uroy']),
            $this->addPupilKeys([1143, 72, 'Sy ra Eysturoy']),
            $this->addPupilKeys([1144, 72, 'Torshavn']),
            $this->addPupilKeys([1145, 72, 'Vaga']),
            $this->addPupilKeys([1146, 73, 'Central']),
            $this->addPupilKeys([1147, 73, 'Eastern']),
            $this->addPupilKeys([1148, 73, 'Northern']),
            $this->addPupilKeys([1149, 73, 'South Pacific']),
            $this->addPupilKeys([1150, 73, 'Western']),
            $this->addPupilKeys([1151, 74, 'Ahvenanmaa']),
            $this->addPupilKeys([1152, 74, 'Etela-Karjala']),
            $this->addPupilKeys([1153, 74, 'Etela-Pohjanmaa']),
            $this->addPupilKeys([1154, 74, 'Etela-Savo']),
            $this->addPupilKeys([1155, 74, 'Etela-Suomen Laani']),
            $this->addPupilKeys([1156, 74, 'Ita-Suomen Laani']),
            $this->addPupilKeys([1157, 74, 'Ita-Uusimaa']),
            $this->addPupilKeys([1158, 74, 'Kainuu']),
            $this->addPupilKeys([1159, 74, 'Kanta-Hame']),
            $this->addPupilKeys([1160, 74, 'Keski-Pohjanmaa']),
            $this->addPupilKeys([1161, 74, 'Keski-Suomi']),
            $this->addPupilKeys([1162, 74, 'Kymenlaakso']),
            $this->addPupilKeys([1163, 74, 'Lansi-Suomen Laani']),
            $this->addPupilKeys([1164, 74, 'Lappi']),
            $this->addPupilKeys([1165, 74, 'Northern Savonia']),
            $this->addPupilKeys([1166, 74, 'Ostrobothnia']),
            $this->addPupilKeys([1167, 74, 'Oulun Laani']),
            $this->addPupilKeys([1168, 74, 'Paijat-Hame']),
            $this->addPupilKeys([1169, 74, 'Pirkanmaa']),
            $this->addPupilKeys([1170, 74, 'Pohjanmaa']),
            $this->addPupilKeys([1171, 74, 'Pohjois-Karjala']),
            $this->addPupilKeys([1172, 74, 'Pohjois-Pohjanmaa']),
            $this->addPupilKeys([1173, 74, 'Pohjois-Savo']),
            $this->addPupilKeys([1174, 74, 'Saarijarvi']),
            $this->addPupilKeys([1175, 74, 'Satakunta']),
            $this->addPupilKeys([1176, 74, 'Southern Savonia']),
            $this->addPupilKeys([1177, 74, 'Tavastia Proper']),
            $this->addPupilKeys([1178, 74, 'Uleaborgs Lan']),
            $this->addPupilKeys([1179, 74, 'Uusimaa']),
            $this->addPupilKeys([1180, 74, 'Varsinais-Suomi']),
            $this->addPupilKeys([1181, 75, 'Ain']),
            $this->addPupilKeys([1182, 75, 'Aisne']),
            $this->addPupilKeys([1183, 75, 'Albi Le Sequestre']),
            $this->addPupilKeys([1184, 75, 'Allier']),
            $this->addPupilKeys([1185, 75, 'Alpes-Cote dAzur']),
            $this->addPupilKeys([1186, 75, 'Alpes-Maritimes']),
            $this->addPupilKeys([1187, 75, 'Alpes-de-Haute-Provence']),
            $this->addPupilKeys([1188, 75, 'Alsace']),
            $this->addPupilKeys([1189, 75, 'Aquitaine']),
            $this->addPupilKeys([1190, 75, 'Ardeche']),
            $this->addPupilKeys([1191, 75, 'Ardennes']),
            $this->addPupilKeys([1192, 75, 'Ariege']),
            $this->addPupilKeys([1193, 75, 'Aube']),
            $this->addPupilKeys([1194, 75, 'Aude']),
            $this->addPupilKeys([1195, 75, 'Auvergne']),
            $this->addPupilKeys([1196, 75, 'Aveyron']),
            $this->addPupilKeys([1197, 75, 'Bas-Rhin']),
            $this->addPupilKeys([1198, 75, 'Basse-Normandie']),
            $this->addPupilKeys([1199, 75, 'Bouches-du-Rhone']),
            $this->addPupilKeys([1200, 75, 'Bourgogne']),
            $this->addPupilKeys([1201, 75, 'Bretagne']),
            $this->addPupilKeys([1202, 75, 'Brittany']),
            $this->addPupilKeys([1203, 75, 'Burgundy']),
            $this->addPupilKeys([1204, 75, 'Calvados']),
            $this->addPupilKeys([1205, 75, 'Cantal']),
            $this->addPupilKeys([1206, 75, 'Cedex']),
            $this->addPupilKeys([1207, 75, 'Centre']),
            $this->addPupilKeys([1208, 75, 'Charente']),
            $this->addPupilKeys([1209, 75, 'Charente-Maritime']),
            $this->addPupilKeys([1210, 75, 'Cher']),
            $this->addPupilKeys([1211, 75, 'Correze']),
            $this->addPupilKeys([1212, 75, 'Corse-du-Sud']),
            $this->addPupilKeys([1213, 75, 'Cote-d\'Or']),
            $this->addPupilKeys([1214, 75, 'Cotes-d\'Armor']),
            $this->addPupilKeys([1215, 75, 'Creuse']),
            $this->addPupilKeys([1216, 75, 'Crolles']),
            $this->addPupilKeys([1217, 75, 'Deux-Sevres']),
            $this->addPupilKeys([1218, 75, 'Dordogne']),
            $this->addPupilKeys([1219, 75, 'Doubs']),
            $this->addPupilKeys([1220, 75, 'Drome']),
            $this->addPupilKeys([1221, 75, 'Essonne']),
            $this->addPupilKeys([1222, 75, 'Eure']),
            $this->addPupilKeys([1223, 75, 'Eure-et-Loir']),
            $this->addPupilKeys([1224, 75, 'Feucherolles']),
            $this->addPupilKeys([1225, 75, 'Finistere']),
            $this->addPupilKeys([1226, 75, 'Franche-Comte']),
            $this->addPupilKeys([1227, 75, 'Gard']),
            $this->addPupilKeys([1228, 75, 'Gers']),
            $this->addPupilKeys([1229, 75, 'Gironde']),
            $this->addPupilKeys([1230, 75, 'Haut-Rhin']),
            $this->addPupilKeys([1231, 75, 'Haute-Corse']),
            $this->addPupilKeys([1232, 75, 'Haute-Garonne']),
            $this->addPupilKeys([1233, 75, 'Haute-Loire']),
            $this->addPupilKeys([1234, 75, 'Haute-Marne']),
            $this->addPupilKeys([1235, 75, 'Haute-Saone']),
            $this->addPupilKeys([1236, 75, 'Haute-Savoie']),
            $this->addPupilKeys([1237, 75, 'Haute-Vienne']),
            $this->addPupilKeys([1238, 75, 'Hautes-Alpes']),
            $this->addPupilKeys([1239, 75, 'Hautes-Pyrenees']),
            $this->addPupilKeys([1240, 75, 'Hauts-de-Seine']),
            $this->addPupilKeys([1241, 75, 'Herault']),
            $this->addPupilKeys([1242, 75, 'Ile-de-France']),
            $this->addPupilKeys([1243, 75, 'Ille-et-Vilaine']),
            $this->addPupilKeys([1244, 75, 'Indre']),
            $this->addPupilKeys([1245, 75, 'Indre-et-Loire']),
            $this->addPupilKeys([1246, 75, 'Isere']),
            $this->addPupilKeys([1247, 75, 'Jura']),
            $this->addPupilKeys([1248, 75, 'Klagenfurt']),
            $this->addPupilKeys([1249, 75, 'Landes']),
            $this->addPupilKeys([1250, 75, 'Languedoc-Roussillon']),
            $this->addPupilKeys([1251, 75, 'Larcay']),
            $this->addPupilKeys([1252, 75, 'Le Castellet']),
            $this->addPupilKeys([1253, 75, 'Le Creusot']),
            $this->addPupilKeys([1254, 75, 'Limousin']),
            $this->addPupilKeys([1255, 75, 'Loir-et-Cher']),
            $this->addPupilKeys([1256, 75, 'Loire']),
            $this->addPupilKeys([1257, 75, 'Loire-Atlantique']),
            $this->addPupilKeys([1258, 75, 'Loiret']),
            $this->addPupilKeys([1259, 75, 'Lorraine']),
            $this->addPupilKeys([1260, 75, 'Lot']),
            $this->addPupilKeys([1261, 75, 'Lot-et-Garonne']),
            $this->addPupilKeys([1262, 75, 'Lower Normandy']),
            $this->addPupilKeys([1263, 75, 'Lozere']),
            $this->addPupilKeys([1264, 75, 'Maine-et-Loire']),
            $this->addPupilKeys([1265, 75, 'Manche']),
            $this->addPupilKeys([1266, 75, 'Marne']),
            $this->addPupilKeys([1267, 75, 'Mayenne']),
            $this->addPupilKeys([1268, 75, 'Meurthe-et-Moselle']),
            $this->addPupilKeys([1269, 75, 'Meuse']),
            $this->addPupilKeys([1270, 75, 'Midi-Pyrenees']),
            $this->addPupilKeys([1271, 75, 'Morbihan']),
            $this->addPupilKeys([1272, 75, 'Moselle']),
            $this->addPupilKeys([1273, 75, 'Nievre']),
            $this->addPupilKeys([1274, 75, 'Nord']),
            $this->addPupilKeys([1275, 75, 'Nord-Pas-de-Calais']),
            $this->addPupilKeys([1276, 75, 'Oise']),
            $this->addPupilKeys([1277, 75, 'Orne']),
            $this->addPupilKeys([1278, 75, 'Paris']),
            $this->addPupilKeys([1279, 75, 'Pas-de-Calais']),
            $this->addPupilKeys([1280, 75, 'Pays de la Loire']),
            $this->addPupilKeys([1281, 75, 'Pays-de-la-Loire']),
            $this->addPupilKeys([1282, 75, 'Picardy']),
            $this->addPupilKeys([1283, 75, 'Puy-de-Dome']),
            $this->addPupilKeys([1284, 75, 'Pyrenees-Atlantiques']),
            $this->addPupilKeys([1285, 75, 'Pyrenees-Orientales']),
            $this->addPupilKeys([1286, 75, 'Quelmes']),
            $this->addPupilKeys([1287, 75, 'Rhone']),
            $this->addPupilKeys([1288, 75, 'Rhone-Alpes']),
            $this->addPupilKeys([1289, 75, 'Saint Ouen']),
            $this->addPupilKeys([1290, 75, 'Saint Viatre']),
            $this->addPupilKeys([1291, 75, 'Saone-et-Loire']),
            $this->addPupilKeys([1292, 75, 'Sarthe']),
            $this->addPupilKeys([1293, 75, 'Savoie']),
            $this->addPupilKeys([1294, 75, 'Seine-Maritime']),
            $this->addPupilKeys([1295, 75, 'Seine-Saint-Denis']),
            $this->addPupilKeys([1296, 75, 'Seine-et-Marne']),
            $this->addPupilKeys([1297, 75, 'Somme']),
            $this->addPupilKeys([1298, 75, 'Sophia Antipolis']),
            $this->addPupilKeys([1299, 75, 'Souvans']),
            $this->addPupilKeys([1300, 75, 'Tarn']),
            $this->addPupilKeys([1301, 75, 'Tarn-et-Garonne']),
            $this->addPupilKeys([1302, 75, 'Territoire de Belfort']),
            $this->addPupilKeys([1303, 75, 'Treignac']),
            $this->addPupilKeys([1304, 75, 'Upper Normandy']),
            $this->addPupilKeys([1305, 75, 'Val-d\'Oise']),
            $this->addPupilKeys([1306, 75, 'Val-de-Marne']),
            $this->addPupilKeys([1307, 75, 'Var']),
            $this->addPupilKeys([1308, 75, 'Vaucluse']),
            $this->addPupilKeys([1309, 75, 'Vellise']),
            $this->addPupilKeys([1310, 75, 'Vendee']),
            $this->addPupilKeys([1311, 75, 'Vienne']),
            $this->addPupilKeys([1312, 75, 'Vosges']),
            $this->addPupilKeys([1313, 75, 'Yonne']),
            $this->addPupilKeys([1314, 75, 'Yvelines']),
            $this->addPupilKeys([1315, 76, 'Cayenne']),
            $this->addPupilKeys([1316, 76, 'Saint-Laurent-du-Maroni']),
            $this->addPupilKeys([1317, 77, 'Iles du Vent']),
            $this->addPupilKeys([1318, 77, 'Iles sous le Vent']),
            $this->addPupilKeys([1319, 77, 'Marquesas']),
            $this->addPupilKeys([1320, 77, 'Tuamotu']),
            $this->addPupilKeys([1321, 77, 'Tubuai']),
            $this->addPupilKeys([1322, 78, 'Amsterdam']),
            $this->addPupilKeys([1323, 78, 'Crozet Islands']),
            $this->addPupilKeys([1324, 78, 'Kerguelen']),
            $this->addPupilKeys([1325, 79, 'Estuaire']),
            $this->addPupilKeys([1326, 79, 'Haut-Ogooue']),
            $this->addPupilKeys([1327, 79, 'Moyen-Ogooue']),
            $this->addPupilKeys([1328, 79, 'Ngounie']),
            $this->addPupilKeys([1329, 79, 'Nyanga']),
            $this->addPupilKeys([1330, 79, 'Ogooue-Ivindo']),
            $this->addPupilKeys([1331, 79, 'Ogooue-Lolo']),
            $this->addPupilKeys([1332, 79, 'Ogooue-Maritime']),
            $this->addPupilKeys([1333, 79, 'Woleu-Ntem']),
            $this->addPupilKeys([1334, 80, 'Banjul']),
            $this->addPupilKeys([1335, 80, 'Basse']),
            $this->addPupilKeys([1336, 80, 'Brikama']),
            $this->addPupilKeys([1337, 80, 'Janjanbureh']),
            $this->addPupilKeys([1338, 80, 'Kanifing']),
            $this->addPupilKeys([1339, 80, 'Kerewan']),
            $this->addPupilKeys([1340, 80, 'Kuntaur']),
            $this->addPupilKeys([1341, 80, 'Mansakonko']),
            $this->addPupilKeys([1342, 81, 'Abhasia']),
            $this->addPupilKeys([1343, 81, 'Ajaria']),
            $this->addPupilKeys([1344, 81, 'Guria']),
            $this->addPupilKeys([1345, 81, 'Imereti']),
            $this->addPupilKeys([1346, 81, 'Kaheti']),
            $this->addPupilKeys([1347, 81, 'Kvemo Kartli']),
            $this->addPupilKeys([1348, 81, 'Mcheta-Mtianeti']),
            $this->addPupilKeys([1349, 81, 'Racha']),
            $this->addPupilKeys([1350, 81, 'Samagrelo-Zemo Svaneti']),
            $this->addPupilKeys([1351, 81, 'Samche-Zhavaheti']),
            $this->addPupilKeys([1352, 81, 'Shida Kartli']),
            $this->addPupilKeys([1353, 81, 'Tbilisi']),
            $this->addPupilKeys([1354, 82, 'Auvergne']),
            $this->addPupilKeys([1355, 82, 'Baden-Wurttemberg']),
            $this->addPupilKeys([1356, 82, 'Bavaria']),
            $this->addPupilKeys([1357, 82, 'Bayern']),
            $this->addPupilKeys([1358, 82, 'Beilstein Wurtt']),
            $this->addPupilKeys([1359, 82, 'Berlin']),
            $this->addPupilKeys([1360, 82, 'Brandenburg']),
            $this->addPupilKeys([1361, 82, 'Bremen']),
            $this->addPupilKeys([1362, 82, 'Dreisbach']),
            $this->addPupilKeys([1363, 82, 'Freistaat Bayern']),
            $this->addPupilKeys([1364, 82, 'Hamburg']),
            $this->addPupilKeys([1365, 82, 'Hannover']),
            $this->addPupilKeys([1366, 82, 'Heroldstatt']),
            $this->addPupilKeys([1367, 82, 'Hessen']),
            $this->addPupilKeys([1368, 82, 'Kortenberg']),
            $this->addPupilKeys([1369, 82, 'Laasdorf']),
            $this->addPupilKeys([1370, 82, 'Land Baden-Wurttemberg']),
            $this->addPupilKeys([1371, 82, 'Land Bayern']),
            $this->addPupilKeys([1372, 82, 'Land Brandenburg']),
            $this->addPupilKeys([1373, 82, 'Land Hessen']),
            $this->addPupilKeys([1374, 82, 'Land Mecklenburg-Vorpommern']),
            $this->addPupilKeys([1375, 82, 'Land Nordrhein-Westfalen']),
            $this->addPupilKeys([1376, 82, 'Land Rheinland-Pfalz']),
            $this->addPupilKeys([1377, 82, 'Land Sachsen']),
            $this->addPupilKeys([1378, 82, 'Land Sachsen-Anhalt']),
            $this->addPupilKeys([1379, 82, 'Land Thuringen']),
            $this->addPupilKeys([1380, 82, 'Lower Saxony']),
            $this->addPupilKeys([1381, 82, 'Mecklenburg-Vorpommern']),
            $this->addPupilKeys([1382, 82, 'Mulfingen']),
            $this->addPupilKeys([1383, 82, 'Munich']),
            $this->addPupilKeys([1384, 82, 'Neubeuern']),
            $this->addPupilKeys([1385, 82, 'Niedersachsen']),
            $this->addPupilKeys([1386, 82, 'Noord-Holland']),
            $this->addPupilKeys([1387, 82, 'Nordrhein-Westfalen']),
            $this->addPupilKeys([1388, 82, 'North Rhine-Westphalia']),
            $this->addPupilKeys([1389, 82, 'Osterode']),
            $this->addPupilKeys([1390, 82, 'Rheinland-Pfalz']),
            $this->addPupilKeys([1391, 82, 'Rhineland-Palatinate']),
            $this->addPupilKeys([1392, 82, 'Saarland']),
            $this->addPupilKeys([1393, 82, 'Sachsen']),
            $this->addPupilKeys([1394, 82, 'Sachsen-Anhalt']),
            $this->addPupilKeys([1395, 82, 'Saxony']),
            $this->addPupilKeys([1396, 82, 'Schleswig-Holstein']),
            $this->addPupilKeys([1397, 82, 'Thuringia']),
            $this->addPupilKeys([1398, 82, 'Webling']),
            $this->addPupilKeys([1399, 82, 'Weinstrabe']),
            $this->addPupilKeys([1400, 82, 'schlobborn']),
            $this->addPupilKeys([1401, 83, 'Ashanti']),
            $this->addPupilKeys([1402, 83, 'Brong-Ahafo']),
            $this->addPupilKeys([1403, 83, 'Central']),
            $this->addPupilKeys([1404, 83, 'Eastern']),
            $this->addPupilKeys([1405, 83, 'Greater Accra']),
            $this->addPupilKeys([1406, 83, 'Northern']),
            $this->addPupilKeys([1407, 83, 'Upper East']),
            $this->addPupilKeys([1408, 83, 'Upper West']),
            $this->addPupilKeys([1409, 83, 'Volta']),
            $this->addPupilKeys([1410, 83, 'Western']),
            $this->addPupilKeys([1411, 84, 'Gibraltar']),
            $this->addPupilKeys([1412, 85, 'Acharnes']),
            $this->addPupilKeys([1413, 85, 'Ahaia']),
            $this->addPupilKeys([1414, 85, 'Aitolia kai Akarnania']),
            $this->addPupilKeys([1415, 85, 'Argolis']),
            $this->addPupilKeys([1416, 85, 'Arkadia']),
            $this->addPupilKeys([1417, 85, 'Arta']),
            $this->addPupilKeys([1418, 85, 'Attica']),
            $this->addPupilKeys([1419, 85, 'Attiki']),
            $this->addPupilKeys([1420, 85, 'Ayion Oros']),
            $this->addPupilKeys([1421, 85, 'Crete']),
            $this->addPupilKeys([1422, 85, 'Dodekanisos']),
            $this->addPupilKeys([1423, 85, 'Drama']),
            $this->addPupilKeys([1424, 85, 'Evia']),
            $this->addPupilKeys([1425, 85, 'Evritania']),
            $this->addPupilKeys([1426, 85, 'Evros']),
            $this->addPupilKeys([1427, 85, 'Evvoia']),
            $this->addPupilKeys([1428, 85, 'Florina']),
            $this->addPupilKeys([1429, 85, 'Fokis']),
            $this->addPupilKeys([1430, 85, 'Fthiotis']),
            $this->addPupilKeys([1431, 85, 'Grevena']),
            $this->addPupilKeys([1432, 85, 'Halandri']),
            $this->addPupilKeys([1433, 85, 'Halkidiki']),
            $this->addPupilKeys([1434, 85, 'Hania']),
            $this->addPupilKeys([1435, 85, 'Heraklion']),
            $this->addPupilKeys([1436, 85, 'Hios']),
            $this->addPupilKeys([1437, 85, 'Ilia']),
            $this->addPupilKeys([1438, 85, 'Imathia']),
            $this->addPupilKeys([1439, 85, 'Ioannina']),
            $this->addPupilKeys([1440, 85, 'Iraklion']),
            $this->addPupilKeys([1441, 85, 'Karditsa']),
            $this->addPupilKeys([1442, 85, 'Kastoria']),
            $this->addPupilKeys([1443, 85, 'Kavala']),
            $this->addPupilKeys([1444, 85, 'Kefallinia']),
            $this->addPupilKeys([1445, 85, 'Kerkira']),
            $this->addPupilKeys([1446, 85, 'Kiklades']),
            $this->addPupilKeys([1447, 85, 'Kilkis']),
            $this->addPupilKeys([1448, 85, 'Korinthia']),
            $this->addPupilKeys([1449, 85, 'Kozani']),
            $this->addPupilKeys([1450, 85, 'Lakonia']),
            $this->addPupilKeys([1451, 85, 'Larisa']),
            $this->addPupilKeys([1452, 85, 'Lasithi']),
            $this->addPupilKeys([1453, 85, 'Lesvos']),
            $this->addPupilKeys([1454, 85, 'Levkas']),
            $this->addPupilKeys([1455, 85, 'Magnisia']),
            $this->addPupilKeys([1456, 85, 'Messinia']),
            $this->addPupilKeys([1457, 85, 'Nomos Attikis']),
            $this->addPupilKeys([1458, 85, 'Nomos Zakynthou']),
            $this->addPupilKeys([1459, 85, 'Pella']),
            $this->addPupilKeys([1460, 85, 'Pieria']),
            $this->addPupilKeys([1461, 85, 'Piraios']),
            $this->addPupilKeys([1462, 85, 'Preveza']),
            $this->addPupilKeys([1463, 85, 'Rethimni']),
            $this->addPupilKeys([1464, 85, 'Rodopi']),
            $this->addPupilKeys([1465, 85, 'Samos']),
            $this->addPupilKeys([1466, 85, 'Serrai']),
            $this->addPupilKeys([1467, 85, 'Thesprotia']),
            $this->addPupilKeys([1468, 85, 'Thessaloniki']),
            $this->addPupilKeys([1469, 85, 'Trikala']),
            $this->addPupilKeys([1470, 85, 'Voiotia']),
            $this->addPupilKeys([1471, 85, 'West Greece']),
            $this->addPupilKeys([1472, 85, 'Xanthi']),
            $this->addPupilKeys([1473, 85, 'Zakinthos']),
            $this->addPupilKeys([1474, 86, 'Aasiaat']),
            $this->addPupilKeys([1475, 86, 'Ammassalik']),
            $this->addPupilKeys([1476, 86, 'Illoqqortoormiut']),
            $this->addPupilKeys([1477, 86, 'Ilulissat']),
            $this->addPupilKeys([1478, 86, 'Ivittuut']),
            $this->addPupilKeys([1479, 86, 'Kangaatsiaq']),
            $this->addPupilKeys([1480, 86, 'Maniitsoq']),
            $this->addPupilKeys([1481, 86, 'Nanortalik']),
            $this->addPupilKeys([1482, 86, 'Narsaq']),
            $this->addPupilKeys([1483, 86, 'Nuuk']),
            $this->addPupilKeys([1484, 86, 'Paamiut']),
            $this->addPupilKeys([1485, 86, 'Qaanaaq']),
            $this->addPupilKeys([1486, 86, 'Qaqortoq']),
            $this->addPupilKeys([1487, 86, 'Qasigiannguit']),
            $this->addPupilKeys([1488, 86, 'Qeqertarsuaq']),
            $this->addPupilKeys([1489, 86, 'Sisimiut']),
            $this->addPupilKeys([1490, 86, 'Udenfor kommunal inddeling']),
            $this->addPupilKeys([1491, 86, 'Upernavik']),
            $this->addPupilKeys([1492, 86, 'Uummannaq']),
            $this->addPupilKeys([1493, 87, 'Carriacou-Petite Martinique']),
            $this->addPupilKeys([1494, 87, 'Saint Andrew']),
            $this->addPupilKeys([1495, 87, 'Saint Davids']),
            $this->addPupilKeys([1496, 87, 'Saint George\'s']),
            $this->addPupilKeys([1497, 87, 'Saint John']),
            $this->addPupilKeys([1498, 87, 'Saint Mark']),
            $this->addPupilKeys([1499, 87, 'Saint Patrick']),
            $this->addPupilKeys([1500, 88, 'Basse-Terre']),
            $this->addPupilKeys([1501, 88, 'Grande-Terre']),
            $this->addPupilKeys([1502, 88, 'Iles des Saintes']),
            $this->addPupilKeys([1503, 88, 'La Desirade']),
            $this->addPupilKeys([1504, 88, 'Marie-Galante']),
            $this->addPupilKeys([1505, 88, 'Saint Barthelemy']),
            $this->addPupilKeys([1506, 88, 'Saint Martin']),
            $this->addPupilKeys([1507, 89, 'Agana Heights']),
            $this->addPupilKeys([1508, 89, 'Agat']),
            $this->addPupilKeys([1509, 89, 'Barrigada']),
            $this->addPupilKeys([1510, 89, 'Chalan-Pago-Ordot']),
            $this->addPupilKeys([1511, 89, 'Dededo']),
            $this->addPupilKeys([1512, 89, 'Hagatna']),
            $this->addPupilKeys([1513, 89, 'Inarajan']),
            $this->addPupilKeys([1514, 89, 'Mangilao']),
            $this->addPupilKeys([1515, 89, 'Merizo']),
            $this->addPupilKeys([1516, 89, 'Mongmong-Toto-Maite']),
            $this->addPupilKeys([1517, 89, 'Santa Rita']),
            $this->addPupilKeys([1518, 89, 'Sinajana']),
            $this->addPupilKeys([1519, 89, 'Talofofo']),
            $this->addPupilKeys([1520, 89, 'Tamuning']),
            $this->addPupilKeys([1521, 89, 'Yigo']),
            $this->addPupilKeys([1522, 89, 'Yona']),
            $this->addPupilKeys([1523, 90, 'Alta Verapaz']),
            $this->addPupilKeys([1524, 90, 'Baja Verapaz']),
            $this->addPupilKeys([1525, 90, 'Chimaltenango']),
            $this->addPupilKeys([1526, 90, 'Chiquimula']),
            $this->addPupilKeys([1527, 90, 'El Progreso']),
            $this->addPupilKeys([1528, 90, 'Escuintla']),
            $this->addPupilKeys([1529, 90, 'Guatemala']),
            $this->addPupilKeys([1530, 90, 'Huehuetenango']),
            $this->addPupilKeys([1531, 90, 'Izabal']),
            $this->addPupilKeys([1532, 90, 'Jalapa']),
            $this->addPupilKeys([1533, 90, 'Jutiapa']),
            $this->addPupilKeys([1534, 90, 'Peten']),
            $this->addPupilKeys([1535, 90, 'Quezaltenango']),
            $this->addPupilKeys([1536, 90, 'Quiche']),
            $this->addPupilKeys([1537, 90, 'Retalhuleu']),
            $this->addPupilKeys([1538, 90, 'Sacatepequez']),
            $this->addPupilKeys([1539, 90, 'San Marcos']),
            $this->addPupilKeys([1540, 90, 'Santa Rosa']),
            $this->addPupilKeys([1541, 90, 'Solola']),
            $this->addPupilKeys([1542, 90, 'Suchitepequez']),
            $this->addPupilKeys([1543, 90, 'Totonicapan']),
            $this->addPupilKeys([1544, 90, 'Zacapa']),
            $this->addPupilKeys([1545, 91, 'Alderney']),
            $this->addPupilKeys([1546, 91, 'Castel']),
            $this->addPupilKeys([1547, 91, 'Forest']),
            $this->addPupilKeys([1548, 91, 'Saint Andrew']),
            $this->addPupilKeys([1549, 91, 'Saint Martin']),
            $this->addPupilKeys([1550, 91, 'Saint Peter Port']),
            $this->addPupilKeys([1551, 91, 'Saint Pierre du Bois']),
            $this->addPupilKeys([1552, 91, 'Saint Sampson']),
            $this->addPupilKeys([1553, 91, 'Saint Saviour']),
            $this->addPupilKeys([1554, 91, 'Sark']),
            $this->addPupilKeys([1555, 91, 'Torteval']),
            $this->addPupilKeys([1556, 91, 'Vale']),
            $this->addPupilKeys([1557, 92, 'Beyla']),
            $this->addPupilKeys([1558, 92, 'Boffa']),
            $this->addPupilKeys([1559, 92, 'Boke']),
            $this->addPupilKeys([1560, 92, 'Conakry']),
            $this->addPupilKeys([1561, 92, 'Coyah']),
            $this->addPupilKeys([1562, 92, 'Dabola']),
            $this->addPupilKeys([1563, 92, 'Dalaba']),
            $this->addPupilKeys([1564, 92, 'Dinguiraye']),
            $this->addPupilKeys([1565, 92, 'Faranah']),
            $this->addPupilKeys([1566, 92, 'Forecariah']),
            $this->addPupilKeys([1567, 92, 'Fria']),
            $this->addPupilKeys([1568, 92, 'Gaoual']),
            $this->addPupilKeys([1569, 92, 'Gueckedou']),
            $this->addPupilKeys([1570, 92, 'Kankan']),
            $this->addPupilKeys([1571, 92, 'Kerouane']),
            $this->addPupilKeys([1572, 92, 'Kindia']),
            $this->addPupilKeys([1573, 92, 'Kissidougou']),
            $this->addPupilKeys([1574, 92, 'Koubia']),
            $this->addPupilKeys([1575, 92, 'Koundara']),
            $this->addPupilKeys([1576, 92, 'Kouroussa']),
            $this->addPupilKeys([1577, 92, 'Labe']),
            $this->addPupilKeys([1578, 92, 'Lola']),
            $this->addPupilKeys([1579, 92, 'Macenta']),
            $this->addPupilKeys([1580, 92, 'Mali']),
            $this->addPupilKeys([1581, 92, 'Mamou']),
            $this->addPupilKeys([1582, 92, 'Mandiana']),
            $this->addPupilKeys([1583, 92, 'Nzerekore']),
            $this->addPupilKeys([1584, 92, 'Pita']),
            $this->addPupilKeys([1585, 92, 'Siguiri']),
            $this->addPupilKeys([1586, 92, 'Telimele']),
            $this->addPupilKeys([1587, 92, 'Tougue']),
            $this->addPupilKeys([1588, 92, 'Yomou']),
            $this->addPupilKeys([1589, 93, 'Bafata']),
            $this->addPupilKeys([1590, 93, 'Bissau']),
            $this->addPupilKeys([1591, 93, 'Bolama']),
            $this->addPupilKeys([1592, 93, 'Cacheu']),
            $this->addPupilKeys([1593, 93, 'Gabu']),
            $this->addPupilKeys([1594, 93, 'Oio']),
            $this->addPupilKeys([1595, 93, 'Quinara']),
            $this->addPupilKeys([1596, 93, 'Tombali']),
            $this->addPupilKeys([1597, 94, 'Barima-Waini']),
            $this->addPupilKeys([1598, 94, 'Cuyuni-Mazaruni']),
            $this->addPupilKeys([1599, 94, 'Demerara-Mahaica']),
            $this->addPupilKeys([1600, 94, 'East Berbice-Corentyne']),
            $this->addPupilKeys([1601, 94, 'Essequibo Islands-West Demerar']),
            $this->addPupilKeys([1602, 94, 'Mahaica-Berbice']),
            $this->addPupilKeys([1603, 94, 'Pomeroon-Supenaam']),
            $this->addPupilKeys([1604, 94, 'Potaro-Siparuni']),
            $this->addPupilKeys([1605, 94, 'Upper Demerara-Berbice']),
            $this->addPupilKeys([1606, 94, 'Upper Takutu-Upper Essequibo']),
            $this->addPupilKeys([1607, 95, 'Artibonite']),
            $this->addPupilKeys([1608, 95, 'Centre']),
            $this->addPupilKeys([1609, 95, 'Grand\'Anse']),
            $this->addPupilKeys([1610, 95, 'Nord']),
            $this->addPupilKeys([1611, 95, 'Nord-Est']),
            $this->addPupilKeys([1612, 95, 'Nord-Ouest']),
            $this->addPupilKeys([1613, 95, 'Ouest']),
            $this->addPupilKeys([1614, 95, 'Sud']),
            $this->addPupilKeys([1615, 95, 'Sud-Est']),
            $this->addPupilKeys([1616, 96, 'Heard and McDonald Islands']),
            $this->addPupilKeys([1617, 97, 'Atlantida']),
            $this->addPupilKeys([1618, 97, 'Choluteca']),
            $this->addPupilKeys([1619, 97, 'Colon']),
            $this->addPupilKeys([1620, 97, 'Comayagua']),
            $this->addPupilKeys([1621, 97, 'Copan']),
            $this->addPupilKeys([1622, 97, 'Cortes']),
            $this->addPupilKeys([1623, 97, 'Distrito Central']),
            $this->addPupilKeys([1624, 97, 'El Paraiso']),
            $this->addPupilKeys([1625, 97, 'Francisco Morazan']),
            $this->addPupilKeys([1626, 97, 'Gracias a Dios']),
            $this->addPupilKeys([1627, 97, 'Intibuca']),
            $this->addPupilKeys([1628, 97, 'Islas de la Bahia']),
            $this->addPupilKeys([1629, 97, 'La Paz']),
            $this->addPupilKeys([1630, 97, 'Lempira']),
            $this->addPupilKeys([1631, 97, 'Ocotepeque']),
            $this->addPupilKeys([1632, 97, 'Olancho']),
            $this->addPupilKeys([1633, 97, 'Santa Barbara']),
            $this->addPupilKeys([1634, 97, 'Valle']),
            $this->addPupilKeys([1635, 97, 'Yoro']),
            $this->addPupilKeys([1636, 98, 'Hong Kong']),
            $this->addPupilKeys([1637, 99, 'Bacs-Kiskun']),
            $this->addPupilKeys([1638, 99, 'Baranya']),
            $this->addPupilKeys([1639, 99, 'Bekes']),
            $this->addPupilKeys([1640, 99, 'Borsod-Abauj-Zemplen']),
            $this->addPupilKeys([1641, 99, 'Budapest']),
            $this->addPupilKeys([1642, 99, 'Csongrad']),
            $this->addPupilKeys([1643, 99, 'Fejer']),
            $this->addPupilKeys([1644, 99, 'Gyor-Moson-Sopron']),
            $this->addPupilKeys([1645, 99, 'Hajdu-Bihar']),
            $this->addPupilKeys([1646, 99, 'Heves']),
            $this->addPupilKeys([1647, 99, 'Jasz-Nagykun-Szolnok']),
            $this->addPupilKeys([1648, 99, 'Komarom-Esztergom']),
            $this->addPupilKeys([1649, 99, 'Nograd']),
            $this->addPupilKeys([1650, 99, 'Pest']),
            $this->addPupilKeys([1651, 99, 'Somogy']),
            $this->addPupilKeys([1652, 99, 'Szabolcs-Szatmar-Bereg']),
            $this->addPupilKeys([1653, 99, 'Tolna']),
            $this->addPupilKeys([1654, 99, 'Vas']),
            $this->addPupilKeys([1655, 99, 'Veszprem']),
            $this->addPupilKeys([1656, 99, 'Zala']),
            $this->addPupilKeys([1657, 100, 'Austurland']),
            $this->addPupilKeys([1658, 100, 'Gullbringusysla']),
            $this->addPupilKeys([1659, 100, 'Hofu borgarsva i']),
            $this->addPupilKeys([1660, 100, 'Nor urland eystra']),
            $this->addPupilKeys([1661, 100, 'Nor urland vestra']),
            $this->addPupilKeys([1662, 100, 'Su urland']),
            $this->addPupilKeys([1663, 100, 'Su urnes']),
            $this->addPupilKeys([1664, 100, 'Vestfir ir']),
            $this->addPupilKeys([1665, 100, 'Vesturland']),
            $this->addPupilKeys([1666, 102, 'Aceh']),
            $this->addPupilKeys([1667, 102, 'Bali']),
            $this->addPupilKeys([1668, 102, 'Bangka-Belitung']),
            $this->addPupilKeys([1669, 102, 'Banten']),
            $this->addPupilKeys([1670, 102, 'Bengkulu']),
            $this->addPupilKeys([1671, 102, 'Gandaria']),
            $this->addPupilKeys([1672, 102, 'Gorontalo']),
            $this->addPupilKeys([1673, 102, 'Jakarta']),
            $this->addPupilKeys([1674, 102, 'Jambi']),
            $this->addPupilKeys([1675, 102, 'Jawa Barat']),
            $this->addPupilKeys([1676, 102, 'Jawa Tengah']),
            $this->addPupilKeys([1677, 102, 'Jawa Timur']),
            $this->addPupilKeys([1678, 102, 'Kalimantan Barat']),
            $this->addPupilKeys([1679, 102, 'Kalimantan Selatan']),
            $this->addPupilKeys([1680, 102, 'Kalimantan Tengah']),
            $this->addPupilKeys([1681, 102, 'Kalimantan Timur']),
            $this->addPupilKeys([1682, 102, 'Kendal']),
            $this->addPupilKeys([1683, 102, 'Lampung']),
            $this->addPupilKeys([1684, 102, 'Maluku']),
            $this->addPupilKeys([1685, 102, 'Maluku Utara']),
            $this->addPupilKeys([1686, 102, 'Nusa Tenggara Barat']),
            $this->addPupilKeys([1687, 102, 'Nusa Tenggara Timur']),
            $this->addPupilKeys([1688, 102, 'Papua']),
            $this->addPupilKeys([1689, 102, 'Riau']),
            $this->addPupilKeys([1690, 102, 'Riau Kepulauan']),
            $this->addPupilKeys([1691, 102, 'Solo']),
            $this->addPupilKeys([1692, 102, 'Sulawesi Selatan']),
            $this->addPupilKeys([1693, 102, 'Sulawesi Tengah']),
            $this->addPupilKeys([1694, 102, 'Sulawesi Tenggara']),
            $this->addPupilKeys([1695, 102, 'Sulawesi Utara']),
            $this->addPupilKeys([1696, 102, 'Sumatera Barat']),
            $this->addPupilKeys([1697, 102, 'Sumatera Selatan']),
            $this->addPupilKeys([1698, 102, 'Sumatera Utara']),
            $this->addPupilKeys([1699, 102, 'Yogyakarta']),
            $this->addPupilKeys([1700, 103, 'Ardabil']),
            $this->addPupilKeys([1701, 103, 'Azarbayjan-e Bakhtari']),
            $this->addPupilKeys([1702, 103, 'Azarbayjan-e Khavari']),
            $this->addPupilKeys([1703, 103, 'Bushehr']),
            $this->addPupilKeys([1704, 103, 'Chahar Mahal-e Bakhtiari']),
            $this->addPupilKeys([1705, 103, 'Esfahan']),
            $this->addPupilKeys([1706, 103, 'Fars']),
            $this->addPupilKeys([1707, 103, 'Gilan']),
            $this->addPupilKeys([1708, 103, 'Golestan']),
            $this->addPupilKeys([1709, 103, 'Hamadan']),
            $this->addPupilKeys([1710, 103, 'Hormozgan']),
            $this->addPupilKeys([1711, 103, 'Ilam']),
            $this->addPupilKeys([1712, 103, 'Kerman']),
            $this->addPupilKeys([1713, 103, 'Kermanshah']),
            $this->addPupilKeys([1714, 103, 'Khorasan']),
            $this->addPupilKeys([1715, 103, 'Khuzestan']),
            $this->addPupilKeys([1716, 103, 'Kohgiluyeh-e Boyerahmad']),
            $this->addPupilKeys([1717, 103, 'Kordestan']),
            $this->addPupilKeys([1718, 103, 'Lorestan']),
            $this->addPupilKeys([1719, 103, 'Markazi']),
            $this->addPupilKeys([1720, 103, 'Mazandaran']),
            $this->addPupilKeys([1721, 103, 'Ostan-e Esfahan']),
            $this->addPupilKeys([1722, 103, 'Qazvin']),
            $this->addPupilKeys([1723, 103, 'Qom']),
            $this->addPupilKeys([1724, 103, 'Semnan']),
            $this->addPupilKeys([1725, 103, 'Sistan-e Baluchestan']),
            $this->addPupilKeys([1726, 103, 'Tehran']),
            $this->addPupilKeys([1727, 103, 'Yazd']),
            $this->addPupilKeys([1728, 103, 'Zanjan']),
            $this->addPupilKeys([1729, 104, 'Babil']),
            $this->addPupilKeys([1730, 104, 'Baghdad']),
            $this->addPupilKeys([1731, 104, 'Dahuk']),
            $this->addPupilKeys([1732, 104, 'Dhi Qar']),
            $this->addPupilKeys([1733, 104, 'Diyala']),
            $this->addPupilKeys([1734, 104, 'Erbil']),
            $this->addPupilKeys([1735, 104, 'Irbil']),
            $this->addPupilKeys([1736, 104, 'Karbala']),
            $this->addPupilKeys([1737, 104, 'Kurdistan']),
            $this->addPupilKeys([1738, 104, 'Maysan']),
            $this->addPupilKeys([1739, 104, 'Ninawa']),
            $this->addPupilKeys([1740, 104, 'Salah-ad-Din']),
            $this->addPupilKeys([1741, 104, 'Wasit']),
            $this->addPupilKeys([1742, 104, 'al-Anbar']),
            $this->addPupilKeys([1743, 104, 'al-Basrah']),
            $this->addPupilKeys([1744, 104, 'al-Muthanna']),
            $this->addPupilKeys([1745, 104, 'al-Qadisiyah']),
            $this->addPupilKeys([1746, 104, 'an-Najaf']),
            $this->addPupilKeys([1747, 104, 'as-Sulaymaniyah']),
            $this->addPupilKeys([1748, 104, 'at-Ta\'mim']),
            $this->addPupilKeys([1749, 105, 'Armagh']),
            $this->addPupilKeys([1750, 105, 'Carlow']),
            $this->addPupilKeys([1751, 105, 'Cavan']),
            $this->addPupilKeys([1752, 105, 'Clare']),
            $this->addPupilKeys([1753, 105, 'Cork']),
            $this->addPupilKeys([1754, 105, 'Donegal']),
            $this->addPupilKeys([1755, 105, 'Dublin']),
            $this->addPupilKeys([1756, 105, 'Galway']),
            $this->addPupilKeys([1757, 105, 'Kerry']),
            $this->addPupilKeys([1758, 105, 'Kildare']),
            $this->addPupilKeys([1759, 105, 'Kilkenny']),
            $this->addPupilKeys([1760, 105, 'Laois']),
            $this->addPupilKeys([1761, 105, 'Leinster']),
            $this->addPupilKeys([1762, 105, 'Leitrim']),
            $this->addPupilKeys([1763, 105, 'Limerick']),
            $this->addPupilKeys([1764, 105, 'Loch Garman']),
            $this->addPupilKeys([1765, 105, 'Longford']),
            $this->addPupilKeys([1766, 105, 'Louth']),
            $this->addPupilKeys([1767, 105, 'Mayo']),
            $this->addPupilKeys([1768, 105, 'Meath']),
            $this->addPupilKeys([1769, 105, 'Monaghan']),
            $this->addPupilKeys([1770, 105, 'Offaly']),
            $this->addPupilKeys([1771, 105, 'Roscommon']),
            $this->addPupilKeys([1772, 105, 'Sligo']),
            $this->addPupilKeys([1773, 105, 'Tipperary North Riding']),
            $this->addPupilKeys([1774, 105, 'Tipperary South Riding']),
            $this->addPupilKeys([1775, 105, 'Ulster']),
            $this->addPupilKeys([1776, 105, 'Waterford']),
            $this->addPupilKeys([1777, 105, 'Westmeath']),
            $this->addPupilKeys([1778, 105, 'Wexford']),
            $this->addPupilKeys([1779, 105, 'Wicklow']),
            $this->addPupilKeys([1780, 106, 'Beit Hanania']),
            $this->addPupilKeys([1781, 106, 'Ben Gurion Airport']),
            $this->addPupilKeys([1782, 106, 'Bethlehem']),
            $this->addPupilKeys([1783, 106, 'Caesarea']),
            $this->addPupilKeys([1784, 106, 'Centre']),
            $this->addPupilKeys([1785, 106, 'Gaza']),
            $this->addPupilKeys([1786, 106, 'Hadaron']),
            $this->addPupilKeys([1787, 106, 'Haifa District']),
            $this->addPupilKeys([1788, 106, 'Hamerkaz']),
            $this->addPupilKeys([1789, 106, 'Hazafon']),
            $this->addPupilKeys([1790, 106, 'Hebron']),
            $this->addPupilKeys([1791, 106, 'Jaffa']),
            $this->addPupilKeys([1792, 106, 'Jerusalem']),
            $this->addPupilKeys([1793, 106, 'Khefa']),
            $this->addPupilKeys([1794, 106, 'Kiryat Yam']),
            $this->addPupilKeys([1795, 106, 'Lower Galilee']),
            $this->addPupilKeys([1796, 106, 'Qalqilya']),
            $this->addPupilKeys([1797, 106, 'Talme Elazar']),
            $this->addPupilKeys([1798, 106, 'Tel Aviv']),
            $this->addPupilKeys([1799, 106, 'Tsafon']),
            $this->addPupilKeys([1800, 106, 'Umm El Fahem']),
            $this->addPupilKeys([1801, 106, 'Yerushalayim']),
            $this->addPupilKeys([1802, 107, 'Abruzzi']),
            $this->addPupilKeys([1803, 107, 'Abruzzo']),
            $this->addPupilKeys([1804, 107, 'Agrigento']),
            $this->addPupilKeys([1805, 107, 'Alessandria']),
            $this->addPupilKeys([1806, 107, 'Ancona']),
            $this->addPupilKeys([1807, 107, 'Arezzo']),
            $this->addPupilKeys([1808, 107, 'Ascoli Piceno']),
            $this->addPupilKeys([1809, 107, 'Asti']),
            $this->addPupilKeys([1810, 107, 'Avellino']),
            $this->addPupilKeys([1811, 107, 'Bari']),
            $this->addPupilKeys([1812, 107, 'Basilicata']),
            $this->addPupilKeys([1813, 107, 'Belluno']),
            $this->addPupilKeys([1814, 107, 'Benevento']),
            $this->addPupilKeys([1815, 107, 'Bergamo']),
            $this->addPupilKeys([1816, 107, 'Biella']),
            $this->addPupilKeys([1817, 107, 'Bologna']),
            $this->addPupilKeys([1818, 107, 'Bolzano']),
            $this->addPupilKeys([1819, 107, 'Brescia']),
            $this->addPupilKeys([1820, 107, 'Brindisi']),
            $this->addPupilKeys([1821, 107, 'Calabria']),
            $this->addPupilKeys([1822, 107, 'Campania']),
            $this->addPupilKeys([1823, 107, 'Cartoceto']),
            $this->addPupilKeys([1824, 107, 'Caserta']),
            $this->addPupilKeys([1825, 107, 'Catania']),
            $this->addPupilKeys([1826, 107, 'Chieti']),
            $this->addPupilKeys([1827, 107, 'Como']),
            $this->addPupilKeys([1828, 107, 'Cosenza']),
            $this->addPupilKeys([1829, 107, 'Cremona']),
            $this->addPupilKeys([1830, 107, 'Cuneo']),
            $this->addPupilKeys([1831, 107, 'Emilia-Romagna']),
            $this->addPupilKeys([1832, 107, 'Ferrara']),
            $this->addPupilKeys([1833, 107, 'Firenze']),
            $this->addPupilKeys([1834, 107, 'Florence']),
            $this->addPupilKeys([1835, 107, 'Forli-Cesena ']),
            $this->addPupilKeys([1836, 107, 'Friuli-Venezia Giulia']),
            $this->addPupilKeys([1837, 107, 'Frosinone']),
            $this->addPupilKeys([1838, 107, 'Genoa']),
            $this->addPupilKeys([1839, 107, 'Gorizia']),
            $this->addPupilKeys([1840, 107, 'L\'Aquila']),
            $this->addPupilKeys([1841, 107, 'Lazio']),
            $this->addPupilKeys([1842, 107, 'Lecce']),
            $this->addPupilKeys([1843, 107, 'Lecco']),
            $this->addPupilKeys([1844, 107, 'Lecco Province']),
            $this->addPupilKeys([1845, 107, 'Liguria']),
            $this->addPupilKeys([1846, 107, 'Lodi']),
            $this->addPupilKeys([1847, 107, 'Lombardia']),
            $this->addPupilKeys([1848, 107, 'Lombardy']),
            $this->addPupilKeys([1849, 107, 'Macerata']),
            $this->addPupilKeys([1850, 107, 'Mantova']),
            $this->addPupilKeys([1851, 107, 'Marche']),
            $this->addPupilKeys([1852, 107, 'Messina']),
            $this->addPupilKeys([1853, 107, 'Milan']),
            $this->addPupilKeys([1854, 107, 'Modena']),
            $this->addPupilKeys([1855, 107, 'Molise']),
            $this->addPupilKeys([1856, 107, 'Molteno']),
            $this->addPupilKeys([1857, 107, 'Montenegro']),
            $this->addPupilKeys([1858, 107, 'Monza and Brianza']),
            $this->addPupilKeys([1859, 107, 'Naples']),
            $this->addPupilKeys([1860, 107, 'Novara']),
            $this->addPupilKeys([1861, 107, 'Padova']),
            $this->addPupilKeys([1862, 107, 'Parma']),
            $this->addPupilKeys([1863, 107, 'Pavia']),
            $this->addPupilKeys([1864, 107, 'Perugia']),
            $this->addPupilKeys([1865, 107, 'Pesaro-Urbino']),
            $this->addPupilKeys([1866, 107, 'Piacenza']),
            $this->addPupilKeys([1867, 107, 'Piedmont']),
            $this->addPupilKeys([1868, 107, 'Piemonte']),
            $this->addPupilKeys([1869, 107, 'Pisa']),
            $this->addPupilKeys([1870, 107, 'Pordenone']),
            $this->addPupilKeys([1871, 107, 'Potenza']),
            $this->addPupilKeys([1872, 107, 'Puglia']),
            $this->addPupilKeys([1873, 107, 'Reggio Emilia']),
            $this->addPupilKeys([1874, 107, 'Rimini']),
            $this->addPupilKeys([1875, 107, 'Roma']),
            $this->addPupilKeys([1876, 107, 'Salerno']),
            $this->addPupilKeys([1877, 107, 'Sardegna']),
            $this->addPupilKeys([1878, 107, 'Sassari']),
            $this->addPupilKeys([1879, 107, 'Savona']),
            $this->addPupilKeys([1880, 107, 'Sicilia']),
            $this->addPupilKeys([1881, 107, 'Siena']),
            $this->addPupilKeys([1882, 107, 'Sondrio']),
            $this->addPupilKeys([1883, 107, 'South Tyrol']),
            $this->addPupilKeys([1884, 107, 'Taranto']),
            $this->addPupilKeys([1885, 107, 'Teramo']),
            $this->addPupilKeys([1886, 107, 'Torino']),
            $this->addPupilKeys([1887, 107, 'Toscana']),
            $this->addPupilKeys([1888, 107, 'Trapani']),
            $this->addPupilKeys([1889, 107, 'Trentino-Alto Adige']),
            $this->addPupilKeys([1890, 107, 'Trento']),
            $this->addPupilKeys([1891, 107, 'Treviso']),
            $this->addPupilKeys([1892, 107, 'Udine']),
            $this->addPupilKeys([1893, 107, 'Umbria']),
            $this->addPupilKeys([1894, 107, 'Valle d\'Aosta']),
            $this->addPupilKeys([1895, 107, 'Varese']),
            $this->addPupilKeys([1896, 107, 'Veneto']),
            $this->addPupilKeys([1897, 107, 'Venezia']),
            $this->addPupilKeys([1898, 107, 'Verbano-Cusio-Ossola']),
            $this->addPupilKeys([1899, 107, 'Vercelli']),
            $this->addPupilKeys([1900, 107, 'Verona']),
            $this->addPupilKeys([1901, 107, 'Vicenza']),
            $this->addPupilKeys([1902, 107, 'Viterbo']),
            $this->addPupilKeys([1903, 108, 'Buxoro Viloyati']),
            $this->addPupilKeys([1904, 108, 'Clarendon']),
            $this->addPupilKeys([1905, 108, 'Hanover']),
            $this->addPupilKeys([1906, 108, 'Kingston']),
            $this->addPupilKeys([1907, 108, 'Manchester']),
            $this->addPupilKeys([1908, 108, 'Portland']),
            $this->addPupilKeys([1909, 108, 'Saint Andrews']),
            $this->addPupilKeys([1910, 108, 'Saint Ann']),
            $this->addPupilKeys([1911, 108, 'Saint Catherine']),
            $this->addPupilKeys([1912, 108, 'Saint Elizabeth']),
            $this->addPupilKeys([1913, 108, 'Saint James']),
            $this->addPupilKeys([1914, 108, 'Saint Mary']),
            $this->addPupilKeys([1915, 108, 'Saint Thomas']),
            $this->addPupilKeys([1916, 108, 'Trelawney']),
            $this->addPupilKeys([1917, 108, 'Westmoreland']),
            $this->addPupilKeys([1918, 109, 'Aichi']),
            $this->addPupilKeys([1919, 109, 'Akita']),
            $this->addPupilKeys([1920, 109, 'Aomori']),
            $this->addPupilKeys([1921, 109, 'Chiba']),
            $this->addPupilKeys([1922, 109, 'Ehime']),
            $this->addPupilKeys([1923, 109, 'Fukui']),
            $this->addPupilKeys([1924, 109, 'Fukuoka']),
            $this->addPupilKeys([1925, 109, 'Fukushima']),
            $this->addPupilKeys([1926, 109, 'Gifu']),
            $this->addPupilKeys([1927, 109, 'Gumma']),
            $this->addPupilKeys([1928, 109, 'Hiroshima']),
            $this->addPupilKeys([1929, 109, 'Hokkaido']),
            $this->addPupilKeys([1930, 109, 'Hyogo']),
            $this->addPupilKeys([1931, 109, 'Ibaraki']),
            $this->addPupilKeys([1932, 109, 'Ishikawa']),
            $this->addPupilKeys([1933, 109, 'Iwate']),
            $this->addPupilKeys([1934, 109, 'Kagawa']),
            $this->addPupilKeys([1935, 109, 'Kagoshima']),
            $this->addPupilKeys([1936, 109, 'Kanagawa']),
            $this->addPupilKeys([1937, 109, 'Kanto']),
            $this->addPupilKeys([1938, 109, 'Kochi']),
            $this->addPupilKeys([1939, 109, 'Kumamoto']),
            $this->addPupilKeys([1940, 109, 'Kyoto']),
            $this->addPupilKeys([1941, 109, 'Mie']),
            $this->addPupilKeys([1942, 109, 'Miyagi']),
            $this->addPupilKeys([1943, 109, 'Miyazaki']),
            $this->addPupilKeys([1944, 109, 'Nagano']),
            $this->addPupilKeys([1945, 109, 'Nagasaki']),
            $this->addPupilKeys([1946, 109, 'Nara']),
            $this->addPupilKeys([1947, 109, 'Niigata']),
            $this->addPupilKeys([1948, 109, 'Oita']),
            $this->addPupilKeys([1949, 109, 'Okayama']),
            $this->addPupilKeys([1950, 109, 'Okinawa']),
            $this->addPupilKeys([1951, 109, 'Osaka']),
            $this->addPupilKeys([1952, 109, 'Saga']),
            $this->addPupilKeys([1953, 109, 'Saitama']),
            $this->addPupilKeys([1954, 109, 'Shiga']),
            $this->addPupilKeys([1955, 109, 'Shimane']),
            $this->addPupilKeys([1956, 109, 'Shizuoka']),
            $this->addPupilKeys([1957, 109, 'Tochigi']),
            $this->addPupilKeys([1958, 109, 'Tokushima']),
            $this->addPupilKeys([1959, 109, 'Tokyo']),
            $this->addPupilKeys([1960, 109, 'Tottori']),
            $this->addPupilKeys([1961, 109, 'Toyama']),
            $this->addPupilKeys([1962, 109, 'Wakayama']),
            $this->addPupilKeys([1963, 109, 'Yamagata']),
            $this->addPupilKeys([1964, 109, 'Yamaguchi']),
            $this->addPupilKeys([1965, 109, 'Yamanashi']),
            $this->addPupilKeys([1966, 110, 'Grouville']),
            $this->addPupilKeys([1967, 110, 'Saint Brelade']),
            $this->addPupilKeys([1968, 110, 'Saint Clement']),
            $this->addPupilKeys([1969, 110, 'Saint Helier']),
            $this->addPupilKeys([1970, 110, 'Saint John']),
            $this->addPupilKeys([1971, 110, 'Saint Lawrence']),
            $this->addPupilKeys([1972, 110, 'Saint Martin']),
            $this->addPupilKeys([1973, 110, 'Saint Mary']),
            $this->addPupilKeys([1974, 110, 'Saint Peter']),
            $this->addPupilKeys([1975, 110, 'Saint Saviour']),
            $this->addPupilKeys([1976, 110, 'Trinity']),
            $this->addPupilKeys([1977, 111, '\'Ajlun']),
            $this->addPupilKeys([1978, 111, 'Amman']),
            $this->addPupilKeys([1979, 111, 'Irbid']),
            $this->addPupilKeys([1980, 111, 'Jarash']),
            $this->addPupilKeys([1981, 111, 'Ma\'an']),
            $this->addPupilKeys([1982, 111, 'Madaba']),
            $this->addPupilKeys([1983, 111, 'al-\'Aqabah']),
            $this->addPupilKeys([1984, 111, 'al-Balqa\'']),
            $this->addPupilKeys([1985, 111, 'al-Karak']),
            $this->addPupilKeys([1986, 111, 'al-Mafraq']),
            $this->addPupilKeys([1987, 111, 'at-Tafilah']),
            $this->addPupilKeys([1988, 111, 'az-Zarqa\'']),
            $this->addPupilKeys([1989, 112, 'Akmecet']),
            $this->addPupilKeys([1990, 112, 'Akmola']),
            $this->addPupilKeys([1991, 112, 'Aktobe']),
            $this->addPupilKeys([1992, 112, 'Almati']),
            $this->addPupilKeys([1993, 112, 'Atirau']),
            $this->addPupilKeys([1994, 112, 'Batis Kazakstan']),
            $this->addPupilKeys([1995, 112, 'Burlinsky Region']),
            $this->addPupilKeys([1996, 112, 'Karagandi']),
            $this->addPupilKeys([1997, 112, 'Kostanay']),
            $this->addPupilKeys([1998, 112, 'Mankistau']),
            $this->addPupilKeys([1999, 112, 'Ontustik Kazakstan']),
            $this->addPupilKeys([2000, 112, 'Pavlodar']),
            $this->addPupilKeys([2001, 112, 'Sigis Kazakstan']),
            $this->addPupilKeys([2002, 112, 'Soltustik Kazakstan']),
            $this->addPupilKeys([2003, 112, 'Taraz']),
            $this->addPupilKeys([2004, 113, 'Central']),
            $this->addPupilKeys([2005, 113, 'Coast']),
            $this->addPupilKeys([2006, 113, 'Eastern']),
            $this->addPupilKeys([2007, 113, 'Nairobi']),
            $this->addPupilKeys([2008, 113, 'North Eastern']),
            $this->addPupilKeys([2009, 113, 'Nyanza']),
            $this->addPupilKeys([2010, 113, 'Rift Valley']),
            $this->addPupilKeys([2011, 113, 'Western']),
            $this->addPupilKeys([2012, 114, 'Abaiang']),
            $this->addPupilKeys([2013, 114, 'Abemana']),
            $this->addPupilKeys([2014, 114, 'Aranuka']),
            $this->addPupilKeys([2015, 114, 'Arorae']),
            $this->addPupilKeys([2016, 114, 'Banaba']),
            $this->addPupilKeys([2017, 114, 'Beru']),
            $this->addPupilKeys([2018, 114, 'Butaritari']),
            $this->addPupilKeys([2019, 114, 'Kiritimati']),
            $this->addPupilKeys([2020, 114, 'Kuria']),
            $this->addPupilKeys([2021, 114, 'Maiana']),
            $this->addPupilKeys([2022, 114, 'Makin']),
            $this->addPupilKeys([2023, 114, 'Marakei']),
            $this->addPupilKeys([2024, 114, 'Nikunau']),
            $this->addPupilKeys([2025, 114, 'Nonouti']),
            $this->addPupilKeys([2026, 114, 'Onotoa']),
            $this->addPupilKeys([2027, 114, 'Phoenix Islands']),
            $this->addPupilKeys([2028, 114, 'Tabiteuea North']),
            $this->addPupilKeys([2029, 114, 'Tabiteuea South']),
            $this->addPupilKeys([2030, 114, 'Tabuaeran']),
            $this->addPupilKeys([2031, 114, 'Tamana']),
            $this->addPupilKeys([2032, 114, 'Tarawa North']),
            $this->addPupilKeys([2033, 114, 'Tarawa South']),
            $this->addPupilKeys([2034, 114, 'Teraina']),
            $this->addPupilKeys([2035, 115, 'Chagangdo']),
            $this->addPupilKeys([2036, 115, 'Hamgyeongbukto']),
            $this->addPupilKeys([2037, 115, 'Hamgyeongnamdo']),
            $this->addPupilKeys([2038, 115, 'Hwanghaebukto']),
            $this->addPupilKeys([2039, 115, 'Hwanghaenamdo']),
            $this->addPupilKeys([2040, 115, 'Kaeseong']),
            $this->addPupilKeys([2041, 115, 'Kangweon']),
            $this->addPupilKeys([2042, 115, 'Nampo']),
            $this->addPupilKeys([2043, 115, 'Pyeonganbukto']),
            $this->addPupilKeys([2044, 115, 'Pyeongannamdo']),
            $this->addPupilKeys([2045, 115, 'Pyeongyang']),
            $this->addPupilKeys([2046, 115, 'Yanggang']),
            $this->addPupilKeys([2047, 116, 'Busan']),
            $this->addPupilKeys([2048, 116, 'Cheju']),
            $this->addPupilKeys([2049, 116, 'Chollabuk']),
            $this->addPupilKeys([2050, 116, 'Chollanam']),
            $this->addPupilKeys([2051, 116, 'Chungbuk']),
            $this->addPupilKeys([2052, 116, 'Chungcheongbuk']),
            $this->addPupilKeys([2053, 116, 'Chungcheongnam']),
            $this->addPupilKeys([2054, 116, 'Chungnam']),
            $this->addPupilKeys([2055, 116, 'Daegu']),
            $this->addPupilKeys([2056, 116, 'Gangwon-do']),
            $this->addPupilKeys([2057, 116, 'Goyang-si']),
            $this->addPupilKeys([2058, 116, 'Gyeonggi-do']),
            $this->addPupilKeys([2059, 116, 'Gyeongsang ']),
            $this->addPupilKeys([2060, 116, 'Gyeongsangnam-do']),
            $this->addPupilKeys([2061, 116, 'Incheon']),
            $this->addPupilKeys([2062, 116, 'Jeju-Si']),
            $this->addPupilKeys([2063, 116, 'Jeonbuk']),
            $this->addPupilKeys([2064, 116, 'Kangweon']),
            $this->addPupilKeys([2065, 116, 'Kwangju']),
            $this->addPupilKeys([2066, 116, 'Kyeonggi']),
            $this->addPupilKeys([2067, 116, 'Kyeongsangbuk']),
            $this->addPupilKeys([2068, 116, 'Kyeongsangnam']),
            $this->addPupilKeys([2069, 116, 'Kyonggi-do']),
            $this->addPupilKeys([2070, 116, 'Kyungbuk-Do']),
            $this->addPupilKeys([2071, 116, 'Kyunggi-Do']),
            $this->addPupilKeys([2072, 116, 'Kyunggi-do']),
            $this->addPupilKeys([2073, 116, 'Pusan']),
            $this->addPupilKeys([2074, 116, 'Seoul']),
            $this->addPupilKeys([2075, 116, 'Sudogwon']),
            $this->addPupilKeys([2076, 116, 'Taegu']),
            $this->addPupilKeys([2077, 116, 'Taejeon']),
            $this->addPupilKeys([2078, 116, 'Taejon-gwangyoksi']),
            $this->addPupilKeys([2079, 116, 'Ulsan']),
            $this->addPupilKeys([2080, 116, 'Wonju']),
            $this->addPupilKeys([2081, 116, 'gwangyoksi']),
            $this->addPupilKeys([2082, 117, 'Al Asimah']),
            $this->addPupilKeys([2083, 117, 'Hawalli']),
            $this->addPupilKeys([2084, 117, 'Mishref']),
            $this->addPupilKeys([2085, 117, 'Qadesiya']),
            $this->addPupilKeys([2086, 117, 'Safat']),
            $this->addPupilKeys([2087, 117, 'Salmiya']),
            $this->addPupilKeys([2088, 117, 'al-Ahmadi']),
            $this->addPupilKeys([2089, 117, 'al-Farwaniyah']),
            $this->addPupilKeys([2090, 117, 'al-Jahra']),
            $this->addPupilKeys([2091, 117, 'al-Kuwayt']),
            $this->addPupilKeys([2092, 118, 'Batken']),
            $this->addPupilKeys([2093, 118, 'Bishkek']),
            $this->addPupilKeys([2094, 118, 'Chui']),
            $this->addPupilKeys([2095, 118, 'Issyk-Kul']),
            $this->addPupilKeys([2096, 118, 'Jalal-Abad']),
            $this->addPupilKeys([2097, 118, 'Naryn']),
            $this->addPupilKeys([2098, 118, 'Osh']),
            $this->addPupilKeys([2099, 118, 'Talas']),
            $this->addPupilKeys([2100, 119, 'Attopu']),
            $this->addPupilKeys([2101, 119, 'Bokeo']),
            $this->addPupilKeys([2102, 119, 'Bolikhamsay']),
            $this->addPupilKeys([2103, 119, 'Champasak']),
            $this->addPupilKeys([2104, 119, 'Houaphanh']),
            $this->addPupilKeys([2105, 119, 'Khammouane']),
            $this->addPupilKeys([2106, 119, 'Luang Nam Tha']),
            $this->addPupilKeys([2107, 119, 'Luang Prabang']),
            $this->addPupilKeys([2108, 119, 'Oudomxay']),
            $this->addPupilKeys([2109, 119, 'Phongsaly']),
            $this->addPupilKeys([2110, 119, 'Saravan']),
            $this->addPupilKeys([2111, 119, 'Savannakhet']),
            $this->addPupilKeys([2112, 119, 'Sekong']),
            $this->addPupilKeys([2113, 119, 'Viangchan Prefecture']),
            $this->addPupilKeys([2114, 119, 'Viangchan Province']),
            $this->addPupilKeys([2115, 119, 'Xaignabury']),
            $this->addPupilKeys([2116, 119, 'Xiang Khuang']),
            $this->addPupilKeys([2117, 120, 'Aizkraukles']),
            $this->addPupilKeys([2118, 120, 'Aluksnes']),
            $this->addPupilKeys([2119, 120, 'Balvu']),
            $this->addPupilKeys([2120, 120, 'Bauskas']),
            $this->addPupilKeys([2121, 120, 'Cesu']),
            $this->addPupilKeys([2122, 120, 'Daugavpils']),
            $this->addPupilKeys([2123, 120, 'Daugavpils City']),
            $this->addPupilKeys([2124, 120, 'Dobeles']),
            $this->addPupilKeys([2125, 120, 'Gulbenes']),
            $this->addPupilKeys([2126, 120, 'Jekabspils']),
            $this->addPupilKeys([2127, 120, 'Jelgava']),
            $this->addPupilKeys([2128, 120, 'Jelgavas']),
            $this->addPupilKeys([2129, 120, 'Jurmala City']),
            $this->addPupilKeys([2130, 120, 'Kraslavas']),
            $this->addPupilKeys([2131, 120, 'Kuldigas']),
            $this->addPupilKeys([2132, 120, 'Liepaja']),
            $this->addPupilKeys([2133, 120, 'Liepajas']),
            $this->addPupilKeys([2134, 120, 'Limbazhu']),
            $this->addPupilKeys([2135, 120, 'Ludzas']),
            $this->addPupilKeys([2136, 120, 'Madonas']),
            $this->addPupilKeys([2137, 120, 'Ogres']),
            $this->addPupilKeys([2138, 120, 'Preilu']),
            $this->addPupilKeys([2139, 120, 'Rezekne']),
            $this->addPupilKeys([2140, 120, 'Rezeknes']),
            $this->addPupilKeys([2141, 120, 'Riga']),
            $this->addPupilKeys([2142, 120, 'Rigas']),
            $this->addPupilKeys([2143, 120, 'Saldus']),
            $this->addPupilKeys([2144, 120, 'Talsu']),
            $this->addPupilKeys([2145, 120, 'Tukuma']),
            $this->addPupilKeys([2146, 120, 'Valkas']),
            $this->addPupilKeys([2147, 120, 'Valmieras']),
            $this->addPupilKeys([2148, 120, 'Ventspils']),
            $this->addPupilKeys([2149, 120, 'Ventspils City']),
            $this->addPupilKeys([2150, 121, 'Beirut']),
            $this->addPupilKeys([2151, 121, 'Jabal Lubnan']),
            $this->addPupilKeys([2152, 121, 'Mohafazat Liban-Nord']),
            $this->addPupilKeys([2153, 121, 'Mohafazat Mont-Liban']),
            $this->addPupilKeys([2154, 121, 'Sidon']),
            $this->addPupilKeys([2155, 121, 'al-Biqa']),
            $this->addPupilKeys([2156, 121, 'al-Janub']),
            $this->addPupilKeys([2157, 121, 'an-Nabatiyah']),
            $this->addPupilKeys([2158, 121, 'ash-Shamal']),
            $this->addPupilKeys([2159, 122, 'Berea']),
            $this->addPupilKeys([2160, 122, 'Butha-Buthe']),
            $this->addPupilKeys([2161, 122, 'Leribe']),
            $this->addPupilKeys([2162, 122, 'Mafeteng']),
            $this->addPupilKeys([2163, 122, 'Maseru']),
            $this->addPupilKeys([2164, 122, 'Mohale\'s Hoek']),
            $this->addPupilKeys([2165, 122, 'Mokhotlong']),
            $this->addPupilKeys([2166, 122, 'Qacha\'s Nek']),
            $this->addPupilKeys([2167, 122, 'Quthing']),
            $this->addPupilKeys([2168, 122, 'Thaba-Tseka']),
            $this->addPupilKeys([2169, 123, 'Bomi']),
            $this->addPupilKeys([2170, 123, 'Bong']),
            $this->addPupilKeys([2171, 123, 'Grand Bassa']),
            $this->addPupilKeys([2172, 123, 'Grand Cape Mount']),
            $this->addPupilKeys([2173, 123, 'Grand Gedeh']),
            $this->addPupilKeys([2174, 123, 'Loffa']),
            $this->addPupilKeys([2175, 123, 'Margibi']),
            $this->addPupilKeys([2176, 123, 'Maryland and Grand Kru']),
            $this->addPupilKeys([2177, 123, 'Montserrado']),
            $this->addPupilKeys([2178, 123, 'Nimba']),
            $this->addPupilKeys([2179, 123, 'Rivercess']),
            $this->addPupilKeys([2180, 123, 'Sinoe']),
            $this->addPupilKeys([2181, 124, 'Ajdabiya']),
            $this->addPupilKeys([2182, 124, 'Fezzan']),
            $this->addPupilKeys([2183, 124, 'Banghazi']),
            $this->addPupilKeys([2184, 124, 'Darnah']),
            $this->addPupilKeys([2185, 124, 'Ghadamis']),
            $this->addPupilKeys([2186, 124, 'Gharyan']),
            $this->addPupilKeys([2187, 124, 'Misratah']),
            $this->addPupilKeys([2188, 124, 'Murzuq']),
            $this->addPupilKeys([2189, 124, 'Sabha']),
            $this->addPupilKeys([2190, 124, 'Sawfajjin']),
            $this->addPupilKeys([2191, 124, 'Surt']),
            $this->addPupilKeys([2192, 124, 'Tarabulus']),
            $this->addPupilKeys([2193, 124, 'Tarhunah']),
            $this->addPupilKeys([2194, 124, 'Tripolitania']),
            $this->addPupilKeys([2195, 124, 'Tubruq']),
            $this->addPupilKeys([2196, 124, 'Yafran']),
            $this->addPupilKeys([2197, 124, 'Zlitan']),
            $this->addPupilKeys([2198, 124, 'al-\'Aziziyah']),
            $this->addPupilKeys([2199, 124, 'al-Fatih']),
            $this->addPupilKeys([2200, 124, 'al-Jabal al Akhdar']),
            $this->addPupilKeys([2201, 124, 'al-Jufrah']),
            $this->addPupilKeys([2202, 124, 'al-Khums']),
            $this->addPupilKeys([2203, 124, 'al-Kufrah']),
            $this->addPupilKeys([2204, 124, 'an-Nuqat al-Khams']),
            $this->addPupilKeys([2205, 124, 'ash-Shati\'']),
            $this->addPupilKeys([2206, 124, 'az-Zawiyah']),
            $this->addPupilKeys([2207, 125, 'Balzers']),
            $this->addPupilKeys([2208, 125, 'Eschen']),
            $this->addPupilKeys([2209, 125, 'Gamprin']),
            $this->addPupilKeys([2210, 125, 'Mauren']),
            $this->addPupilKeys([2211, 125, 'Planken']),
            $this->addPupilKeys([2212, 125, 'Ruggell']),
            $this->addPupilKeys([2213, 125, 'Schaan']),
            $this->addPupilKeys([2214, 125, 'Schellenberg']),
            $this->addPupilKeys([2215, 125, 'Triesen']),
            $this->addPupilKeys([2216, 125, 'Triesenberg']),
            $this->addPupilKeys([2217, 125, 'Vaduz']),
            $this->addPupilKeys([2218, 126, 'Alytaus']),
            $this->addPupilKeys([2219, 126, 'Anyksciai']),
            $this->addPupilKeys([2220, 126, 'Kauno']),
            $this->addPupilKeys([2221, 126, 'Klaipedos']),
            $this->addPupilKeys([2222, 126, 'Marijampoles']),
            $this->addPupilKeys([2223, 126, 'Panevezhio']),
            $this->addPupilKeys([2224, 126, 'Panevezys']),
            $this->addPupilKeys([2225, 126, 'Shiauliu']),
            $this->addPupilKeys([2226, 126, 'Taurages']),
            $this->addPupilKeys([2227, 126, 'Telshiu']),
            $this->addPupilKeys([2228, 126, 'Telsiai']),
            $this->addPupilKeys([2229, 126, 'Utenos']),
            $this->addPupilKeys([2230, 126, 'Vilniaus']),
            $this->addPupilKeys([2231, 127, 'Capellen']),
            $this->addPupilKeys([2232, 127, 'Clervaux']),
            $this->addPupilKeys([2233, 127, 'Diekirch']),
            $this->addPupilKeys([2234, 127, 'Echternach']),
            $this->addPupilKeys([2235, 127, 'Esch-sur-Alzette']),
            $this->addPupilKeys([2236, 127, 'Grevenmacher']),
            $this->addPupilKeys([2237, 127, 'Luxembourg']),
            $this->addPupilKeys([2238, 127, 'Mersch']),
            $this->addPupilKeys([2239, 127, 'Redange']),
            $this->addPupilKeys([2240, 127, 'Remich']),
            $this->addPupilKeys([2241, 127, 'Vianden']),
            $this->addPupilKeys([2242, 127, 'Wiltz']),
            $this->addPupilKeys([2243, 128, 'Macau']),
            $this->addPupilKeys([2244, 129, 'Berovo']),
            $this->addPupilKeys([2245, 129, 'Bitola']),
            $this->addPupilKeys([2246, 129, 'Brod']),
            $this->addPupilKeys([2247, 129, 'Debar']),
            $this->addPupilKeys([2248, 129, 'Delchevo']),
            $this->addPupilKeys([2249, 129, 'Demir Hisar']),
            $this->addPupilKeys([2250, 129, 'Gevgelija']),
            $this->addPupilKeys([2251, 129, 'Gostivar']),
            $this->addPupilKeys([2252, 129, 'Kavadarci']),
            $this->addPupilKeys([2253, 129, 'Kichevo']),
            $this->addPupilKeys([2254, 129, 'Kochani']),
            $this->addPupilKeys([2255, 129, 'Kratovo']),
            $this->addPupilKeys([2256, 129, 'Kriva Palanka']),
            $this->addPupilKeys([2257, 129, 'Krushevo']),
            $this->addPupilKeys([2258, 129, 'Kumanovo']),
            $this->addPupilKeys([2259, 129, 'Negotino']),
            $this->addPupilKeys([2260, 129, 'Ohrid']),
            $this->addPupilKeys([2261, 129, 'Prilep']),
            $this->addPupilKeys([2262, 129, 'Probishtip']),
            $this->addPupilKeys([2263, 129, 'Radovish']),
            $this->addPupilKeys([2264, 129, 'Resen']),
            $this->addPupilKeys([2265, 129, 'Shtip']),
            $this->addPupilKeys([2266, 129, 'Skopje']),
            $this->addPupilKeys([2267, 129, 'Struga']),
            $this->addPupilKeys([2268, 129, 'Strumica']),
            $this->addPupilKeys([2269, 129, 'Sveti Nikole']),
            $this->addPupilKeys([2270, 129, 'Tetovo']),
            $this->addPupilKeys([2271, 129, 'Valandovo']),
            $this->addPupilKeys([2272, 129, 'Veles']),
            $this->addPupilKeys([2273, 129, 'Vinica']),
            $this->addPupilKeys([2274, 130, 'Antananarivo']),
            $this->addPupilKeys([2275, 130, 'Antsiranana']),
            $this->addPupilKeys([2276, 130, 'Fianarantsoa']),
            $this->addPupilKeys([2277, 130, 'Mahajanga']),
            $this->addPupilKeys([2278, 130, 'Toamasina']),
            $this->addPupilKeys([2279, 130, 'Toliary']),
            $this->addPupilKeys([2280, 131, 'Balaka']),
            $this->addPupilKeys([2281, 131, 'Blantyre City']),
            $this->addPupilKeys([2282, 131, 'Chikwawa']),
            $this->addPupilKeys([2283, 131, 'Chiradzulu']),
            $this->addPupilKeys([2284, 131, 'Chitipa']),
            $this->addPupilKeys([2285, 131, 'Dedza']),
            $this->addPupilKeys([2286, 131, 'Dowa']),
            $this->addPupilKeys([2287, 131, 'Karonga']),
            $this->addPupilKeys([2288, 131, 'Kasungu']),
            $this->addPupilKeys([2289, 131, 'Lilongwe City']),
            $this->addPupilKeys([2290, 131, 'Machinga']),
            $this->addPupilKeys([2291, 131, 'Mangochi']),
            $this->addPupilKeys([2292, 131, 'Mchinji']),
            $this->addPupilKeys([2293, 131, 'Mulanje']),
            $this->addPupilKeys([2294, 131, 'Mwanza']),
            $this->addPupilKeys([2295, 131, 'Mzimba']),
            $this->addPupilKeys([2296, 131, 'Mzuzu City']),
            $this->addPupilKeys([2297, 131, 'Nkhata Bay']),
            $this->addPupilKeys([2298, 131, 'Nkhotakota']),
            $this->addPupilKeys([2299, 131, 'Nsanje']),
            $this->addPupilKeys([2300, 131, 'Ntcheu']),
            $this->addPupilKeys([2301, 131, 'Ntchisi']),
            $this->addPupilKeys([2302, 131, 'Phalombe']),
            $this->addPupilKeys([2303, 131, 'Rumphi']),
            $this->addPupilKeys([2304, 131, 'Salima']),
            $this->addPupilKeys([2305, 131, 'Thyolo']),
            $this->addPupilKeys([2306, 131, 'Zomba Municipality']),
            $this->addPupilKeys([2307, 132, 'Johor']),
            $this->addPupilKeys([2308, 132, 'Kedah']),
            $this->addPupilKeys([2309, 132, 'Kelantan']),
            $this->addPupilKeys([2310, 132, 'Kuala Lumpur']),
            $this->addPupilKeys([2311, 132, 'Labuan']),
            $this->addPupilKeys([2312, 132, 'Melaka']),
            $this->addPupilKeys([2313, 132, 'Negeri Johor']),
            $this->addPupilKeys([2314, 132, 'Negeri Sembilan']),
            $this->addPupilKeys([2315, 132, 'Pahang']),
            $this->addPupilKeys([2316, 132, 'Penang']),
            $this->addPupilKeys([2317, 132, 'Perak']),
            $this->addPupilKeys([2318, 132, 'Perlis']),
            $this->addPupilKeys([2319, 132, 'Pulau Pinang']),
            $this->addPupilKeys([2320, 132, 'Sabah']),
            $this->addPupilKeys([2321, 132, 'Sarawak']),
            $this->addPupilKeys([2322, 132, 'Selangor']),
            $this->addPupilKeys([2323, 132, 'Sembilan']),
            $this->addPupilKeys([2324, 132, 'Terengganu']),
            $this->addPupilKeys([2325, 133, 'Alif Alif']),
            $this->addPupilKeys([2326, 133, 'Alif Dhaal']),
            $this->addPupilKeys([2327, 133, 'Baa']),
            $this->addPupilKeys([2328, 133, 'Dhaal']),
            $this->addPupilKeys([2329, 133, 'Faaf']),
            $this->addPupilKeys([2330, 133, 'Gaaf Alif']),
            $this->addPupilKeys([2331, 133, 'Gaaf Dhaal']),
            $this->addPupilKeys([2332, 133, 'Ghaviyani']),
            $this->addPupilKeys([2333, 133, 'Haa Alif']),
            $this->addPupilKeys([2334, 133, 'Haa Dhaal']),
            $this->addPupilKeys([2335, 133, 'Kaaf']),
            $this->addPupilKeys([2336, 133, 'Laam']),
            $this->addPupilKeys([2337, 133, 'Lhaviyani']),
            $this->addPupilKeys([2338, 133, 'Male']),
            $this->addPupilKeys([2339, 133, 'Miim']),
            $this->addPupilKeys([2340, 133, 'Nuun']),
            $this->addPupilKeys([2341, 133, 'Raa']),
            $this->addPupilKeys([2342, 133, 'Shaviyani']),
            $this->addPupilKeys([2343, 133, 'Siin']),
            $this->addPupilKeys([2344, 133, 'Thaa']),
            $this->addPupilKeys([2345, 133, 'Vaav']),
            $this->addPupilKeys([2346, 134, 'Bamako']),
            $this->addPupilKeys([2347, 134, 'Gao']),
            $this->addPupilKeys([2348, 134, 'Kayes']),
            $this->addPupilKeys([2349, 134, 'Kidal']),
            $this->addPupilKeys([2350, 134, 'Koulikoro']),
            $this->addPupilKeys([2351, 134, 'Mopti']),
            $this->addPupilKeys([2352, 134, 'Segou']),
            $this->addPupilKeys([2353, 134, 'Sikasso']),
            $this->addPupilKeys([2354, 134, 'Tombouctou']),
            $this->addPupilKeys([2355, 135, 'Gozo and Comino']),
            $this->addPupilKeys([2356, 135, 'Inner Harbour']),
            $this->addPupilKeys([2357, 135, 'Northern']),
            $this->addPupilKeys([2358, 135, 'Outer Harbour']),
            $this->addPupilKeys([2359, 135, 'South Eastern']),
            $this->addPupilKeys([2360, 135, 'Valletta']),
            $this->addPupilKeys([2361, 135, 'Western']),
            $this->addPupilKeys([2362, 136, 'Castletown']),
            $this->addPupilKeys([2363, 136, 'Douglas']),
            $this->addPupilKeys([2364, 136, 'Laxey']),
            $this->addPupilKeys([2365, 136, 'Onchan']),
            $this->addPupilKeys([2366, 136, 'Peel']),
            $this->addPupilKeys([2367, 136, 'Port Erin']),
            $this->addPupilKeys([2368, 136, 'Port Saint Mary']),
            $this->addPupilKeys([2369, 136, 'Ramsey']),
            $this->addPupilKeys([2370, 137, 'Ailinlaplap']),
            $this->addPupilKeys([2371, 137, 'Ailuk']),
            $this->addPupilKeys([2372, 137, 'Arno']),
            $this->addPupilKeys([2373, 137, 'Aur']),
            $this->addPupilKeys([2374, 137, 'Bikini']),
            $this->addPupilKeys([2375, 137, 'Ebon']),
            $this->addPupilKeys([2376, 137, 'Enewetak']),
            $this->addPupilKeys([2377, 137, 'Jabat']),
            $this->addPupilKeys([2378, 137, 'Jaluit']),
            $this->addPupilKeys([2379, 137, 'Kili']),
            $this->addPupilKeys([2380, 137, 'Kwajalein']),
            $this->addPupilKeys([2381, 137, 'Lae']),
            $this->addPupilKeys([2382, 137, 'Lib']),
            $this->addPupilKeys([2383, 137, 'Likiep']),
            $this->addPupilKeys([2384, 137, 'Majuro']),
            $this->addPupilKeys([2385, 137, 'Maloelap']),
            $this->addPupilKeys([2386, 137, 'Mejit']),
            $this->addPupilKeys([2387, 137, 'Mili']),
            $this->addPupilKeys([2388, 137, 'Namorik']),
            $this->addPupilKeys([2389, 137, 'Namu']),
            $this->addPupilKeys([2390, 137, 'Rongelap']),
            $this->addPupilKeys([2391, 137, 'Ujae']),
            $this->addPupilKeys([2392, 137, 'Utrik']),
            $this->addPupilKeys([2393, 137, 'Wotho']),
            $this->addPupilKeys([2394, 137, 'Wotje']),
            $this->addPupilKeys([2395, 138, 'Fort-de-France']),
            $this->addPupilKeys([2396, 138, 'La Trinite']),
            $this->addPupilKeys([2397, 138, 'Le Marin']),
            $this->addPupilKeys([2398, 138, 'Saint-Pierre']),
            $this->addPupilKeys([2399, 139, 'Adrar']),
            $this->addPupilKeys([2400, 139, 'Assaba']),
            $this->addPupilKeys([2401, 139, 'Brakna']),
            $this->addPupilKeys([2402, 139, 'Dhakhlat Nawadibu']),
            $this->addPupilKeys([2403, 139, 'Hudh-al-Gharbi']),
            $this->addPupilKeys([2404, 139, 'Hudh-ash-Sharqi']),
            $this->addPupilKeys([2405, 139, 'Inshiri']),
            $this->addPupilKeys([2406, 139, 'Nawakshut']),
            $this->addPupilKeys([2407, 139, 'Qidimagha']),
            $this->addPupilKeys([2408, 139, 'Qurqul']),
            $this->addPupilKeys([2409, 139, 'Taqant']),
            $this->addPupilKeys([2410, 139, 'Tiris Zammur']),
            $this->addPupilKeys([2411, 139, 'Trarza']),
            $this->addPupilKeys([2412, 140, 'Black River']),
            $this->addPupilKeys([2413, 140, 'Eau Coulee']),
            $this->addPupilKeys([2414, 140, 'Flacq']),
            $this->addPupilKeys([2415, 140, 'Floreal']),
            $this->addPupilKeys([2416, 140, 'Grand Port']),
            $this->addPupilKeys([2417, 140, 'Moka']),
            $this->addPupilKeys([2418, 140, 'Pamplempousses']),
            $this->addPupilKeys([2419, 140, 'Plaines Wilhelm']),
            $this->addPupilKeys([2420, 140, 'Port Louis']),
            $this->addPupilKeys([2421, 140, 'Riviere du Rempart']),
            $this->addPupilKeys([2422, 140, 'Rodrigues']),
            $this->addPupilKeys([2423, 140, 'Rose Hill']),
            $this->addPupilKeys([2424, 140, 'Savanne']),
            $this->addPupilKeys([2425, 141, 'Mayotte']),
            $this->addPupilKeys([2426, 141, 'Pamanzi']),
            $this->addPupilKeys([2427, 142, 'Aguascalientes']),
            $this->addPupilKeys([2428, 142, 'Baja California']),
            $this->addPupilKeys([2429, 142, 'Baja California Sur']),
            $this->addPupilKeys([2430, 142, 'Campeche']),
            $this->addPupilKeys([2431, 142, 'Chiapas']),
            $this->addPupilKeys([2432, 142, 'Chihuahua']),
            $this->addPupilKeys([2433, 142, 'Coahuila']),
            $this->addPupilKeys([2434, 142, 'Colima']),
            $this->addPupilKeys([2435, 142, 'Distrito Federal']),
            $this->addPupilKeys([2436, 142, 'Durango']),
            $this->addPupilKeys([2437, 142, 'Estado de Mexico']),
            $this->addPupilKeys([2438, 142, 'Guanajuato']),
            $this->addPupilKeys([2439, 142, 'Guerrero']),
            $this->addPupilKeys([2440, 142, 'Hidalgo']),
            $this->addPupilKeys([2441, 142, 'Jalisco']),
            $this->addPupilKeys([2442, 142, 'Mexico']),
            $this->addPupilKeys([2443, 142, 'Michoacan']),
            $this->addPupilKeys([2444, 142, 'Morelos']),
            $this->addPupilKeys([2445, 142, 'Nayarit']),
            $this->addPupilKeys([2446, 142, 'Nuevo Leon']),
            $this->addPupilKeys([2447, 142, 'Oaxaca']),
            $this->addPupilKeys([2448, 142, 'Puebla']),
            $this->addPupilKeys([2449, 142, 'Queretaro']),
            $this->addPupilKeys([2450, 142, 'Quintana Roo']),
            $this->addPupilKeys([2451, 142, 'San Luis Potosi']),
            $this->addPupilKeys([2452, 142, 'Sinaloa']),
            $this->addPupilKeys([2453, 142, 'Sonora']),
            $this->addPupilKeys([2454, 142, 'Tabasco']),
            $this->addPupilKeys([2455, 142, 'Tamaulipas']),
            $this->addPupilKeys([2456, 142, 'Tlaxcala']),
            $this->addPupilKeys([2457, 142, 'Veracruz']),
            $this->addPupilKeys([2458, 142, 'Yucatan']),
            $this->addPupilKeys([2459, 142, 'Zacatecas']),
            $this->addPupilKeys([2460, 143, 'Chuuk']),
            $this->addPupilKeys([2461, 143, 'Kusaie']),
            $this->addPupilKeys([2462, 143, 'Pohnpei']),
            $this->addPupilKeys([2463, 143, 'Yap']),
            $this->addPupilKeys([2464, 144, 'Balti']),
            $this->addPupilKeys([2465, 144, 'Cahul']),
            $this->addPupilKeys([2466, 144, 'Chisinau']),
            $this->addPupilKeys([2467, 144, 'Chisinau Oras']),
            $this->addPupilKeys([2468, 144, 'Edinet']),
            $this->addPupilKeys([2469, 144, 'Gagauzia']),
            $this->addPupilKeys([2470, 144, 'Lapusna']),
            $this->addPupilKeys([2471, 144, 'Orhei']),
            $this->addPupilKeys([2472, 144, 'Soroca']),
            $this->addPupilKeys([2473, 144, 'Taraclia']),
            $this->addPupilKeys([2474, 144, 'Tighina']),
            $this->addPupilKeys([2475, 144, 'Transnistria']),
            $this->addPupilKeys([2476, 144, 'Ungheni']),
            $this->addPupilKeys([2477, 145, 'Fontvieille']),
            $this->addPupilKeys([2478, 145, 'La Condamine']),
            $this->addPupilKeys([2479, 145, 'Monaco-Ville']),
            $this->addPupilKeys([2480, 145, 'Monte Carlo']),
            $this->addPupilKeys([2481, 146, 'Arhangaj']),
            $this->addPupilKeys([2482, 146, 'Bajan-Olgij']),
            $this->addPupilKeys([2483, 146, 'Bajanhongor']),
            $this->addPupilKeys([2484, 146, 'Bulgan']),
            $this->addPupilKeys([2485, 146, 'Darhan-Uul']),
            $this->addPupilKeys([2486, 146, 'Dornod']),
            $this->addPupilKeys([2487, 146, 'Dornogovi']),
            $this->addPupilKeys([2488, 146, 'Dundgovi']),
            $this->addPupilKeys([2489, 146, 'Govi-Altaj']),
            $this->addPupilKeys([2490, 146, 'Govisumber']),
            $this->addPupilKeys([2491, 146, 'Hentij']),
            $this->addPupilKeys([2492, 146, 'Hovd']),
            $this->addPupilKeys([2493, 146, 'Hovsgol']),
            $this->addPupilKeys([2494, 146, 'Omnogovi']),
            $this->addPupilKeys([2495, 146, 'Orhon']),
            $this->addPupilKeys([2496, 146, 'Ovorhangaj']),
            $this->addPupilKeys([2497, 146, 'Selenge']),
            $this->addPupilKeys([2498, 146, 'Suhbaatar']),
            $this->addPupilKeys([2499, 146, 'Tov']),
            $this->addPupilKeys([2500, 146, 'Ulaanbaatar']),
            $this->addPupilKeys([2501, 146, 'Uvs']),
            $this->addPupilKeys([2502, 146, 'Zavhan']),
            $this->addPupilKeys([2503, 147, 'Montserrat']),
            $this->addPupilKeys([2504, 148, 'Agadir']),
            $this->addPupilKeys([2505, 148, 'Casablanca']),
            $this->addPupilKeys([2506, 148, 'Chaouia-Ouardigha']),
            $this->addPupilKeys([2507, 148, 'Doukkala-Abda']),
            $this->addPupilKeys([2508, 148, 'Fes-Boulemane']),
            $this->addPupilKeys([2509, 148, 'Gharb-Chrarda-Beni Hssen']),
            $this->addPupilKeys([2510, 148, 'Guelmim']),
            $this->addPupilKeys([2511, 148, 'Kenitra']),
            $this->addPupilKeys([2512, 148, 'Marrakech-Tensift-Al Haouz']),
            $this->addPupilKeys([2513, 148, 'Meknes-Tafilalet']),
            $this->addPupilKeys([2514, 148, 'Oriental']),
            $this->addPupilKeys([2515, 148, 'Oujda']),
            $this->addPupilKeys([2516, 148, 'Province de Tanger']),
            $this->addPupilKeys([2517, 148, 'Rabat-Sale-Zammour-Zaer']),
            $this->addPupilKeys([2518, 148, 'Sala Al Jadida']),
            $this->addPupilKeys([2519, 148, 'Settat']),
            $this->addPupilKeys([2520, 148, 'Souss Massa-Draa']),
            $this->addPupilKeys([2521, 148, 'Tadla-Azilal']),
            $this->addPupilKeys([2522, 148, 'Tangier-Tetouan']),
            $this->addPupilKeys([2523, 148, 'Taza-Al Hoceima-Taounate']),
            $this->addPupilKeys([2524, 148, 'Wilaya de Casablanca']),
            $this->addPupilKeys([2525, 148, 'Wilaya de Rabat-Sale']),
            $this->addPupilKeys([2526, 149, 'Cabo Delgado']),
            $this->addPupilKeys([2527, 149, 'Gaza']),
            $this->addPupilKeys([2528, 149, 'Inhambane']),
            $this->addPupilKeys([2529, 149, 'Manica']),
            $this->addPupilKeys([2530, 149, 'Maputo']),
            $this->addPupilKeys([2531, 149, 'Maputo Provincia']),
            $this->addPupilKeys([2532, 149, 'Nampula']),
            $this->addPupilKeys([2533, 149, 'Niassa']),
            $this->addPupilKeys([2534, 149, 'Sofala']),
            $this->addPupilKeys([2535, 149, 'Tete']),
            $this->addPupilKeys([2536, 149, 'Zambezia']),
            $this->addPupilKeys([2537, 150, 'Ayeyarwady']),
            $this->addPupilKeys([2538, 150, 'Bago']),
            $this->addPupilKeys([2539, 150, 'Chin']),
            $this->addPupilKeys([2540, 150, 'Kachin']),
            $this->addPupilKeys([2541, 150, 'Kayah']),
            $this->addPupilKeys([2542, 150, 'Kayin']),
            $this->addPupilKeys([2543, 150, 'Magway']),
            $this->addPupilKeys([2544, 150, 'Mandalay']),
            $this->addPupilKeys([2545, 150, 'Mon']),
            $this->addPupilKeys([2546, 150, 'Nay Pyi Taw']),
            $this->addPupilKeys([2547, 150, 'Rakhine']),
            $this->addPupilKeys([2548, 150, 'Sagaing']),
            $this->addPupilKeys([2549, 150, 'Shan']),
            $this->addPupilKeys([2550, 150, 'Tanintharyi']),
            $this->addPupilKeys([2551, 150, 'Yangon']),
            $this->addPupilKeys([2552, 151, 'Caprivi']),
            $this->addPupilKeys([2553, 151, 'Erongo']),
            $this->addPupilKeys([2554, 151, 'Hardap']),
            $this->addPupilKeys([2555, 151, 'Karas']),
            $this->addPupilKeys([2556, 151, 'Kavango']),
            $this->addPupilKeys([2557, 151, 'Khomas']),
            $this->addPupilKeys([2558, 151, 'Kunene']),
            $this->addPupilKeys([2559, 151, 'Ohangwena']),
            $this->addPupilKeys([2560, 151, 'Omaheke']),
            $this->addPupilKeys([2561, 151, 'Omusati']),
            $this->addPupilKeys([2562, 151, 'Oshana']),
            $this->addPupilKeys([2563, 151, 'Oshikoto']),
            $this->addPupilKeys([2564, 151, 'Otjozondjupa']),
            $this->addPupilKeys([2565, 152, 'Yaren']),
            $this->addPupilKeys([2566, 153, 'Bagmati']),
            $this->addPupilKeys([2567, 153, 'Bheri']),
            $this->addPupilKeys([2568, 153, 'Dhawalagiri']),
            $this->addPupilKeys([2569, 153, 'Gandaki']),
            $this->addPupilKeys([2570, 153, 'Janakpur']),
            $this->addPupilKeys([2571, 153, 'Karnali']),
            $this->addPupilKeys([2572, 153, 'Koshi']),
            $this->addPupilKeys([2573, 153, 'Lumbini']),
            $this->addPupilKeys([2574, 153, 'Mahakali']),
            $this->addPupilKeys([2575, 153, 'Mechi']),
            $this->addPupilKeys([2576, 153, 'Narayani']),
            $this->addPupilKeys([2577, 153, 'Rapti']),
            $this->addPupilKeys([2578, 153, 'Sagarmatha']),
            $this->addPupilKeys([2579, 153, 'Seti']),
            $this->addPupilKeys([2580, 154, 'Bonaire']),
            $this->addPupilKeys([2581, 154, 'Curacao']),
            $this->addPupilKeys([2582, 154, 'Saba']),
            $this->addPupilKeys([2583, 154, 'Sint Eustatius']),
            $this->addPupilKeys([2584, 154, 'Sint Maarten']),
            $this->addPupilKeys([2585, 155, 'Amsterdam']),
            $this->addPupilKeys([2586, 155, 'Benelux']),
            $this->addPupilKeys([2587, 155, 'Drenthe']),
            $this->addPupilKeys([2588, 155, 'Flevoland']),
            $this->addPupilKeys([2589, 155, 'Friesland']),
            $this->addPupilKeys([2590, 155, 'Gelderland']),
            $this->addPupilKeys([2591, 155, 'Groningen']),
            $this->addPupilKeys([2592, 155, 'Limburg']),
            $this->addPupilKeys([2593, 155, 'Noord-Brabant']),
            $this->addPupilKeys([2594, 155, 'Noord-Holland']),
            $this->addPupilKeys([2595, 155, 'Overijssel']),
            $this->addPupilKeys([2596, 155, 'South Holland']),
            $this->addPupilKeys([2597, 155, 'Utrecht']),
            $this->addPupilKeys([2598, 155, 'Zeeland']),
            $this->addPupilKeys([2599, 155, 'Zuid-Holland']),
            $this->addPupilKeys([2600, 156, 'Iles']),
            $this->addPupilKeys([2601, 156, 'Nord']),
            $this->addPupilKeys([2602, 156, 'Sud']),
            $this->addPupilKeys([2603, 157, 'Area Outside Region']),
            $this->addPupilKeys([2604, 157, 'Auckland']),
            $this->addPupilKeys([2605, 157, 'Bay of Plenty']),
            $this->addPupilKeys([2606, 157, 'Canterbury']),
            $this->addPupilKeys([2607, 157, 'Christchurch']),
            $this->addPupilKeys([2608, 157, 'Gisborne']),
            $this->addPupilKeys([2609, 157, 'Hawke\'s Bay']),
            $this->addPupilKeys([2610, 157, 'Manawatu-Wanganui']),
            $this->addPupilKeys([2611, 157, 'Marlborough']),
            $this->addPupilKeys([2612, 157, 'Nelson']),
            $this->addPupilKeys([2613, 157, 'Northland']),
            $this->addPupilKeys([2614, 157, 'Otago']),
            $this->addPupilKeys([2615, 157, 'Rodney']),
            $this->addPupilKeys([2616, 157, 'Southland']),
            $this->addPupilKeys([2617, 157, 'Taranaki']),
            $this->addPupilKeys([2618, 157, 'Tasman']),
            $this->addPupilKeys([2619, 157, 'Waikato']),
            $this->addPupilKeys([2620, 157, 'Wellington']),
            $this->addPupilKeys([2621, 157, 'West Coast']),
            $this->addPupilKeys([2622, 158, 'Atlantico Norte']),
            $this->addPupilKeys([2623, 158, 'Atlantico Sur']),
            $this->addPupilKeys([2624, 158, 'Boaco']),
            $this->addPupilKeys([2625, 158, 'Carazo']),
            $this->addPupilKeys([2626, 158, 'Chinandega']),
            $this->addPupilKeys([2627, 158, 'Chontales']),
            $this->addPupilKeys([2628, 158, 'Esteli']),
            $this->addPupilKeys([2629, 158, 'Granada']),
            $this->addPupilKeys([2630, 158, 'Jinotega']),
            $this->addPupilKeys([2631, 158, 'Leon']),
            $this->addPupilKeys([2632, 158, 'Madriz']),
            $this->addPupilKeys([2633, 158, 'Managua']),
            $this->addPupilKeys([2634, 158, 'Masaya']),
            $this->addPupilKeys([2635, 158, 'Matagalpa']),
            $this->addPupilKeys([2636, 158, 'Nueva Segovia']),
            $this->addPupilKeys([2637, 158, 'Rio San Juan']),
            $this->addPupilKeys([2638, 158, 'Rivas']),
            $this->addPupilKeys([2639, 159, 'Agadez']),
            $this->addPupilKeys([2640, 159, 'Diffa']),
            $this->addPupilKeys([2641, 159, 'Dosso']),
            $this->addPupilKeys([2642, 159, 'Maradi']),
            $this->addPupilKeys([2643, 159, 'Niamey']),
            $this->addPupilKeys([2644, 159, 'Tahoua']),
            $this->addPupilKeys([2645, 159, 'Tillabery']),
            $this->addPupilKeys([2646, 159, 'Zinder']),
            $this->addPupilKeys([2647, 160, 'Abia']),
            $this->addPupilKeys([2648, 160, 'Abuja Federal Capital Territor']),
            $this->addPupilKeys([2649, 160, 'Adamawa']),
            $this->addPupilKeys([2650, 160, 'Akwa Ibom']),
            $this->addPupilKeys([2651, 160, 'Anambra']),
            $this->addPupilKeys([2652, 160, 'Bauchi']),
            $this->addPupilKeys([2653, 160, 'Bayelsa']),
            $this->addPupilKeys([2654, 160, 'Benue']),
            $this->addPupilKeys([2655, 160, 'Borno']),
            $this->addPupilKeys([2656, 160, 'Cross River']),
            $this->addPupilKeys([2657, 160, 'Delta']),
            $this->addPupilKeys([2658, 160, 'Ebonyi']),
            $this->addPupilKeys([2659, 160, 'Edo']),
            $this->addPupilKeys([2660, 160, 'Ekiti']),
            $this->addPupilKeys([2661, 160, 'Enugu']),
            $this->addPupilKeys([2662, 160, 'Gombe']),
            $this->addPupilKeys([2663, 160, 'Imo']),
            $this->addPupilKeys([2664, 160, 'Jigawa']),
            $this->addPupilKeys([2665, 160, 'Kaduna']),
            $this->addPupilKeys([2666, 160, 'Kano']),
            $this->addPupilKeys([2667, 160, 'Katsina']),
            $this->addPupilKeys([2668, 160, 'Kebbi']),
            $this->addPupilKeys([2669, 160, 'Kogi']),
            $this->addPupilKeys([2670, 160, 'Kwara']),
            $this->addPupilKeys([2671, 160, 'Lagos']),
            $this->addPupilKeys([2672, 160, 'Nassarawa']),
            $this->addPupilKeys([2673, 160, 'Niger']),
            $this->addPupilKeys([2674, 160, 'Ogun']),
            $this->addPupilKeys([2675, 160, 'Ondo']),
            $this->addPupilKeys([2676, 160, 'Osun']),
            $this->addPupilKeys([2677, 160, 'Oyo']),
            $this->addPupilKeys([2678, 160, 'Plateau']),
            $this->addPupilKeys([2679, 160, 'Rivers']),
            $this->addPupilKeys([2680, 160, 'Sokoto']),
            $this->addPupilKeys([2681, 160, 'Taraba']),
            $this->addPupilKeys([2682, 160, 'Yobe']),
            $this->addPupilKeys([2683, 160, 'Zamfara']),
            $this->addPupilKeys([2684, 161, 'Niue']),
            $this->addPupilKeys([2685, 162, 'Norfolk Island']),
            $this->addPupilKeys([2686, 163, 'Northern Islands']),
            $this->addPupilKeys([2687, 163, 'Rota']),
            $this->addPupilKeys([2688, 163, 'Saipan']),
            $this->addPupilKeys([2689, 163, 'Tinian']),
            $this->addPupilKeys([2690, 164, 'Akershus']),
            $this->addPupilKeys([2691, 164, 'Aust Agder']),
            $this->addPupilKeys([2692, 164, 'Bergen']),
            $this->addPupilKeys([2693, 164, 'Buskerud']),
            $this->addPupilKeys([2694, 164, 'Finnmark']),
            $this->addPupilKeys([2695, 164, 'Hedmark']),
            $this->addPupilKeys([2696, 164, 'Hordaland']),
            $this->addPupilKeys([2697, 164, 'Moere og Romsdal']),
            $this->addPupilKeys([2698, 164, 'Nord Trondelag']),
            $this->addPupilKeys([2699, 164, 'Nordland']),
            $this->addPupilKeys([2700, 164, 'Oestfold']),
            $this->addPupilKeys([2701, 164, 'Oppland']),
            $this->addPupilKeys([2702, 164, 'Oslo']),
            $this->addPupilKeys([2703, 164, 'Rogaland']),
            $this->addPupilKeys([2704, 164, 'Soer Troendelag']),
            $this->addPupilKeys([2705, 164, 'Sogn og Fjordane']),
            $this->addPupilKeys([2706, 164, 'Stavern']),
            $this->addPupilKeys([2707, 164, 'Sykkylven']),
            $this->addPupilKeys([2708, 164, 'Telemark']),
            $this->addPupilKeys([2709, 164, 'Troms']),
            $this->addPupilKeys([2710, 164, 'Vest Agder']),
            $this->addPupilKeys([2711, 164, 'Vestfold']),
            $this->addPupilKeys([2712, 164, 'ÃƒÂ˜stfold']),
            $this->addPupilKeys([2713, 165, 'Al Buraimi']),
            $this->addPupilKeys([2714, 165, 'Dhufar']),
            $this->addPupilKeys([2715, 165, 'Masqat']),
            $this->addPupilKeys([2716, 165, 'Musandam']),
            $this->addPupilKeys([2717, 165, 'Rusayl']),
            $this->addPupilKeys([2718, 165, 'Wadi Kabir']),
            $this->addPupilKeys([2719, 165, 'ad-Dakhiliyah']),
            $this->addPupilKeys([2720, 165, 'adh-Dhahirah']),
            $this->addPupilKeys([2721, 165, 'al-Batinah']),
            $this->addPupilKeys([2722, 165, 'ash-Sharqiyah']),
            $this->addPupilKeys([2723, 166, 'Baluchistan']),
            $this->addPupilKeys([2724, 166, 'Federal Capital Area']),
            $this->addPupilKeys([2725, 166, 'Federally administered Tribal ']),
            $this->addPupilKeys([2726, 166, 'North-West Frontier']),
            $this->addPupilKeys([2727, 166, 'Northern Areas']),
            $this->addPupilKeys([2728, 166, 'Punjab']),
            $this->addPupilKeys([2729, 166, 'Sind']),
            $this->addPupilKeys([2730, 167, 'Aimeliik']),
            $this->addPupilKeys([2731, 167, 'Airai']),
            $this->addPupilKeys([2732, 167, 'Angaur']),
            $this->addPupilKeys([2733, 167, 'Hatobohei']),
            $this->addPupilKeys([2734, 167, 'Kayangel']),
            $this->addPupilKeys([2735, 167, 'Koror']),
            $this->addPupilKeys([2736, 167, 'Melekeok']),
            $this->addPupilKeys([2737, 167, 'Ngaraard']),
            $this->addPupilKeys([2738, 167, 'Ngardmau']),
            $this->addPupilKeys([2739, 167, 'Ngaremlengui']),
            $this->addPupilKeys([2740, 167, 'Ngatpang']),
            $this->addPupilKeys([2741, 167, 'Ngchesar']),
            $this->addPupilKeys([2742, 167, 'Ngerchelong']),
            $this->addPupilKeys([2743, 167, 'Ngiwal']),
            $this->addPupilKeys([2744, 167, 'Peleliu']),
            $this->addPupilKeys([2745, 167, 'Sonsorol']),
            $this->addPupilKeys([2746, 168, 'Ariha']),
            $this->addPupilKeys([2747, 168, 'Bayt Lahm']),
            $this->addPupilKeys([2748, 168, 'Bethlehem']),
            $this->addPupilKeys([2749, 168, 'Dayr-al-Balah']),
            $this->addPupilKeys([2750, 168, 'Ghazzah']),
            $this->addPupilKeys([2751, 168, 'Ghazzah ash-Shamaliyah']),
            $this->addPupilKeys([2752, 168, 'Janin']),
            $this->addPupilKeys([2753, 168, 'Khan Yunis']),
            $this->addPupilKeys([2754, 168, 'Nabulus']),
            $this->addPupilKeys([2755, 168, 'Qalqilyah']),
            $this->addPupilKeys([2756, 168, 'Rafah']),
            $this->addPupilKeys([2757, 168, 'Ram Allah wal-Birah']),
            $this->addPupilKeys([2758, 168, 'Salfit']),
            $this->addPupilKeys([2759, 168, 'Tubas']),
            $this->addPupilKeys([2760, 168, 'Tulkarm']),
            $this->addPupilKeys([2761, 168, 'al-Khalil']),
            $this->addPupilKeys([2762, 168, 'al-Quds']),
            $this->addPupilKeys([2763, 169, 'Bocas del Toro']),
            $this->addPupilKeys([2764, 169, 'Chiriqui']),
            $this->addPupilKeys([2765, 169, 'Cocle']),
            $this->addPupilKeys([2766, 169, 'Colon']),
            $this->addPupilKeys([2767, 169, 'Darien']),
            $this->addPupilKeys([2768, 169, 'Embera']),
            $this->addPupilKeys([2769, 169, 'Herrera']),
            $this->addPupilKeys([2770, 169, 'Kuna Yala']),
            $this->addPupilKeys([2771, 169, 'Los Santos']),
            $this->addPupilKeys([2772, 169, 'Ngobe Bugle']),
            $this->addPupilKeys([2773, 169, 'Panama']),
            $this->addPupilKeys([2774, 169, 'Veraguas']),
            $this->addPupilKeys([2775, 170, 'East New Britain']),
            $this->addPupilKeys([2776, 170, 'East Sepik']),
            $this->addPupilKeys([2777, 170, 'Eastern Highlands']),
            $this->addPupilKeys([2778, 170, 'Enga']),
            $this->addPupilKeys([2779, 170, 'Fly River']),
            $this->addPupilKeys([2780, 170, 'Gulf']),
            $this->addPupilKeys([2781, 170, 'Madang']),
            $this->addPupilKeys([2782, 170, 'Manus']),
            $this->addPupilKeys([2783, 170, 'Milne Bay']),
            $this->addPupilKeys([2784, 170, 'Morobe']),
            $this->addPupilKeys([2785, 170, 'National Capital District']),
            $this->addPupilKeys([2786, 170, 'New Ireland']),
            $this->addPupilKeys([2787, 170, 'North Solomons']),
            $this->addPupilKeys([2788, 170, 'Oro']),
            $this->addPupilKeys([2789, 170, 'Sandaun']),
            $this->addPupilKeys([2790, 170, 'Simbu']),
            $this->addPupilKeys([2791, 170, 'Southern Highlands']),
            $this->addPupilKeys([2792, 170, 'West New Britain']),
            $this->addPupilKeys([2793, 170, 'Western Highlands']),
            $this->addPupilKeys([2794, 171, 'Alto Paraguay']),
            $this->addPupilKeys([2795, 171, 'Alto Parana']),
            $this->addPupilKeys([2796, 171, 'Amambay']),
            $this->addPupilKeys([2797, 171, 'Asuncion']),
            $this->addPupilKeys([2798, 171, 'Boqueron']),
            $this->addPupilKeys([2799, 171, 'Caaguazu']),
            $this->addPupilKeys([2800, 171, 'Caazapa']),
            $this->addPupilKeys([2801, 171, 'Canendiyu']),
            $this->addPupilKeys([2802, 171, 'Central']),
            $this->addPupilKeys([2803, 171, 'Concepcion']),
            $this->addPupilKeys([2804, 171, 'Cordillera']),
            $this->addPupilKeys([2805, 171, 'Guaira']),
            $this->addPupilKeys([2806, 171, 'Itapua']),
            $this->addPupilKeys([2807, 171, 'Misiones']),
            $this->addPupilKeys([2808, 171, 'Neembucu']),
            $this->addPupilKeys([2809, 171, 'Paraguari']),
            $this->addPupilKeys([2810, 171, 'Presidente Hayes']),
            $this->addPupilKeys([2811, 171, 'San Pedro']),
            $this->addPupilKeys([2812, 172, 'Amazonas']),
            $this->addPupilKeys([2813, 172, 'Ancash']),
            $this->addPupilKeys([2814, 172, 'Apurimac']),
            $this->addPupilKeys([2815, 172, 'Arequipa']),
            $this->addPupilKeys([2816, 172, 'Ayacucho']),
            $this->addPupilKeys([2817, 172, 'Cajamarca']),
            $this->addPupilKeys([2818, 172, 'Cusco']),
            $this->addPupilKeys([2819, 172, 'Huancavelica']),
            $this->addPupilKeys([2820, 172, 'Huanuco']),
            $this->addPupilKeys([2821, 172, 'Ica']),
            $this->addPupilKeys([2822, 172, 'Junin']),
            $this->addPupilKeys([2823, 172, 'La Libertad']),
            $this->addPupilKeys([2824, 172, 'Lambayeque']),
            $this->addPupilKeys([2825, 172, 'Lima y Callao']),
            $this->addPupilKeys([2826, 172, 'Loreto']),
            $this->addPupilKeys([2827, 172, 'Madre de Dios']),
            $this->addPupilKeys([2828, 172, 'Moquegua']),
            $this->addPupilKeys([2829, 172, 'Pasco']),
            $this->addPupilKeys([2830, 172, 'Piura']),
            $this->addPupilKeys([2831, 172, 'Puno']),
            $this->addPupilKeys([2832, 172, 'San Martin']),
            $this->addPupilKeys([2833, 172, 'Tacna']),
            $this->addPupilKeys([2834, 172, 'Tumbes']),
            $this->addPupilKeys([2835, 172, 'Ucayali']),
            $this->addPupilKeys([2836, 173, 'Batangas']),
            $this->addPupilKeys([2837, 173, 'Bicol']),
            $this->addPupilKeys([2838, 173, 'Bulacan']),
            $this->addPupilKeys([2839, 173, 'Cagayan']),
            $this->addPupilKeys([2840, 173, 'Caraga']),
            $this->addPupilKeys([2841, 173, 'Central Luzon']),
            $this->addPupilKeys([2842, 173, 'Central Mindanao']),
            $this->addPupilKeys([2843, 173, 'Central Visayas']),
            $this->addPupilKeys([2844, 173, 'Cordillera']),
            $this->addPupilKeys([2845, 173, 'Davao']),
            $this->addPupilKeys([2846, 173, 'Eastern Visayas']),
            $this->addPupilKeys([2847, 173, 'Greater Metropolitan Area']),
            $this->addPupilKeys([2848, 173, 'Ilocos']),
            $this->addPupilKeys([2849, 173, 'Laguna']),
            $this->addPupilKeys([2850, 173, 'Luzon']),
            $this->addPupilKeys([2851, 173, 'Mactan']),
            $this->addPupilKeys([2852, 173, 'Metropolitan Manila Area']),
            $this->addPupilKeys([2853, 173, 'Muslim Mindanao']),
            $this->addPupilKeys([2854, 173, 'Northern Mindanao']),
            $this->addPupilKeys([2855, 173, 'Southern Mindanao']),
            $this->addPupilKeys([2856, 173, 'Southern Tagalog']),
            $this->addPupilKeys([2857, 173, 'Western Mindanao']),
            $this->addPupilKeys([2858, 173, 'Western Visayas']),
            $this->addPupilKeys([2859, 174, 'Pitcairn Island']),
            $this->addPupilKeys([2860, 175, 'Biale Blota']),
            $this->addPupilKeys([2861, 175, 'Dobroszyce']),
            $this->addPupilKeys([2862, 175, 'Dolnoslaskie']),
            $this->addPupilKeys([2863, 175, 'Dziekanow Lesny']),
            $this->addPupilKeys([2864, 175, 'Hopowo']),
            $this->addPupilKeys([2865, 175, 'Kartuzy']),
            $this->addPupilKeys([2866, 175, 'Koscian']),
            $this->addPupilKeys([2867, 175, 'Krakow']),
            $this->addPupilKeys([2868, 175, 'Kujawsko-Pomorskie']),
            $this->addPupilKeys([2869, 175, 'Lodzkie']),
            $this->addPupilKeys([2870, 175, 'Lubelskie']),
            $this->addPupilKeys([2871, 175, 'Lubuskie']),
            $this->addPupilKeys([2872, 175, 'Malomice']),
            $this->addPupilKeys([2873, 175, 'Malopolskie']),
            $this->addPupilKeys([2874, 175, 'Mazowieckie']),
            $this->addPupilKeys([2875, 175, 'Mirkow']),
            $this->addPupilKeys([2876, 175, 'Opolskie']),
            $this->addPupilKeys([2877, 175, 'Ostrowiec']),
            $this->addPupilKeys([2878, 175, 'Podkarpackie']),
            $this->addPupilKeys([2879, 175, 'Podlaskie']),
            $this->addPupilKeys([2880, 175, 'Polska']),
            $this->addPupilKeys([2881, 175, 'Pomorskie']),
            $this->addPupilKeys([2882, 175, 'Poznan']),
            $this->addPupilKeys([2883, 175, 'Pruszkow']),
            $this->addPupilKeys([2884, 175, 'Rymanowska']),
            $this->addPupilKeys([2885, 175, 'Rzeszow']),
            $this->addPupilKeys([2886, 175, 'Slaskie']),
            $this->addPupilKeys([2887, 175, 'Stare Pole']),
            $this->addPupilKeys([2888, 175, 'Swietokrzyskie']),
            $this->addPupilKeys([2889, 175, 'Warminsko-Mazurskie']),
            $this->addPupilKeys([2890, 175, 'Warsaw']),
            $this->addPupilKeys([2891, 175, 'Wejherowo']),
            $this->addPupilKeys([2892, 175, 'Wielkopolskie']),
            $this->addPupilKeys([2893, 175, 'Wroclaw']),
            $this->addPupilKeys([2894, 175, 'Zachodnio-Pomorskie']),
            $this->addPupilKeys([2895, 175, 'Zukowo']),
            $this->addPupilKeys([2896, 176, 'Abrantes']),
            $this->addPupilKeys([2897, 176, 'Acores']),
            $this->addPupilKeys([2898, 176, 'Alentejo']),
            $this->addPupilKeys([2899, 176, 'Algarve']),
            $this->addPupilKeys([2900, 176, 'Braga']),
            $this->addPupilKeys([2901, 176, 'Centro']),
            $this->addPupilKeys([2902, 176, 'Distrito de Leiria']),
            $this->addPupilKeys([2903, 176, 'Distrito de Viana do Castelo']),
            $this->addPupilKeys([2904, 176, 'Distrito de Vila Real']),
            $this->addPupilKeys([2905, 176, 'Distrito do Porto']),
            $this->addPupilKeys([2906, 176, 'Lisboa e Vale do Tejo']),
            $this->addPupilKeys([2907, 176, 'Madeira']),
            $this->addPupilKeys([2908, 176, 'Norte']),
            $this->addPupilKeys([2909, 176, 'Paivas']),
            $this->addPupilKeys([2910, 177, 'Arecibo']),
            $this->addPupilKeys([2911, 177, 'Bayamon']),
            $this->addPupilKeys([2912, 177, 'Carolina']),
            $this->addPupilKeys([2913, 177, 'Florida']),
            $this->addPupilKeys([2914, 177, 'Guayama']),
            $this->addPupilKeys([2915, 177, 'Humacao']),
            $this->addPupilKeys([2916, 177, 'Mayaguez-Aguadilla']),
            $this->addPupilKeys([2917, 177, 'Ponce']),
            $this->addPupilKeys([2918, 177, 'Salinas']),
            $this->addPupilKeys([2919, 177, 'San Juan']),
            $this->addPupilKeys([2920, 178, 'Doha']),
            $this->addPupilKeys([2921, 178, 'Jarian-al-Batnah']),
            $this->addPupilKeys([2922, 178, 'Umm Salal']),
            $this->addPupilKeys([2923, 178, 'ad-Dawhah']),
            $this->addPupilKeys([2924, 178, 'al-Ghuwayriyah']),
            $this->addPupilKeys([2925, 178, 'al-Jumayliyah']),
            $this->addPupilKeys([2926, 178, 'al-Khawr']),
            $this->addPupilKeys([2927, 178, 'al-Wakrah']),
            $this->addPupilKeys([2928, 178, 'ar-Rayyan']),
            $this->addPupilKeys([2929, 178, 'ash-Shamal']),
            $this->addPupilKeys([2930, 179, 'Saint-Benoit']),
            $this->addPupilKeys([2931, 179, 'Saint-Denis']),
            $this->addPupilKeys([2932, 179, 'Saint-Paul']),
            $this->addPupilKeys([2933, 179, 'Saint-Pierre']),
            $this->addPupilKeys([2934, 180, 'Alba']),
            $this->addPupilKeys([2935, 180, 'Arad']),
            $this->addPupilKeys([2936, 180, 'Arges']),
            $this->addPupilKeys([2937, 180, 'Bacau']),
            $this->addPupilKeys([2938, 180, 'Bihor']),
            $this->addPupilKeys([2939, 180, 'Bistrita-Nasaud']),
            $this->addPupilKeys([2940, 180, 'Botosani']),
            $this->addPupilKeys([2941, 180, 'Braila']),
            $this->addPupilKeys([2942, 180, 'Brasov']),
            $this->addPupilKeys([2943, 180, 'Bucuresti']),
            $this->addPupilKeys([2944, 180, 'Buzau']),
            $this->addPupilKeys([2945, 180, 'Calarasi']),
            $this->addPupilKeys([2946, 180, 'Caras-Severin']),
            $this->addPupilKeys([2947, 180, 'Cluj']),
            $this->addPupilKeys([2948, 180, 'Constanta']),
            $this->addPupilKeys([2949, 180, 'Covasna']),
            $this->addPupilKeys([2950, 180, 'Dambovita']),
            $this->addPupilKeys([2951, 180, 'Dolj']),
            $this->addPupilKeys([2952, 180, 'Galati']),
            $this->addPupilKeys([2953, 180, 'Giurgiu']),
            $this->addPupilKeys([2954, 180, 'Gorj']),
            $this->addPupilKeys([2955, 180, 'Harghita']),
            $this->addPupilKeys([2956, 180, 'Hunedoara']),
            $this->addPupilKeys([2957, 180, 'Ialomita']),
            $this->addPupilKeys([2958, 180, 'Iasi']),
            $this->addPupilKeys([2959, 180, 'Ilfov']),
            $this->addPupilKeys([2960, 180, 'Maramures']),
            $this->addPupilKeys([2961, 180, 'Mehedinti']),
            $this->addPupilKeys([2962, 180, 'Mures']),
            $this->addPupilKeys([2963, 180, 'Neamt']),
            $this->addPupilKeys([2964, 180, 'Olt']),
            $this->addPupilKeys([2965, 180, 'Prahova']),
            $this->addPupilKeys([2966, 180, 'Salaj']),
            $this->addPupilKeys([2967, 180, 'Satu Mare']),
            $this->addPupilKeys([2968, 180, 'Sibiu']),
            $this->addPupilKeys([2969, 180, 'Sondelor']),
            $this->addPupilKeys([2970, 180, 'Suceava']),
            $this->addPupilKeys([2971, 180, 'Teleorman']),
            $this->addPupilKeys([2972, 180, 'Timis']),
            $this->addPupilKeys([2973, 180, 'Tulcea']),
            $this->addPupilKeys([2974, 180, 'Valcea']),
            $this->addPupilKeys([2975, 180, 'Vaslui']),
            $this->addPupilKeys([2976, 180, 'Vrancea']),
            $this->addPupilKeys([2977, 181, 'Adygeja']),
            $this->addPupilKeys([2978, 181, 'Aga']),
            $this->addPupilKeys([2979, 181, 'Alanija']),
            $this->addPupilKeys([2980, 181, 'Altaj']),
            $this->addPupilKeys([2981, 181, 'Amur']),
            $this->addPupilKeys([2982, 181, 'Arhangelsk']),
            $this->addPupilKeys([2983, 181, 'Astrahan']),
            $this->addPupilKeys([2984, 181, 'Bashkortostan']),
            $this->addPupilKeys([2985, 181, 'Belgorod']),
            $this->addPupilKeys([2986, 181, 'Brjansk']),
            $this->addPupilKeys([2987, 181, 'Burjatija']),
            $this->addPupilKeys([2988, 181, 'Chechenija']),
            $this->addPupilKeys([2989, 181, 'Cheljabinsk']),
            $this->addPupilKeys([2990, 181, 'Chita']),
            $this->addPupilKeys([2991, 181, 'Chukotka']),
            $this->addPupilKeys([2992, 181, 'Chuvashija']),
            $this->addPupilKeys([2993, 181, 'Dagestan']),
            $this->addPupilKeys([2994, 181, 'Evenkija']),
            $this->addPupilKeys([2995, 181, 'Gorno-Altaj']),
            $this->addPupilKeys([2996, 181, 'Habarovsk']),
            $this->addPupilKeys([2997, 181, 'Hakasija']),
            $this->addPupilKeys([2998, 181, 'Hanty-Mansija']),
            $this->addPupilKeys([2999, 181, 'Ingusetija']),
            $this->addPupilKeys([3000, 181, 'Irkutsk']),
            $this->addPupilKeys([3001, 181, 'Ivanovo']),
            $this->addPupilKeys([3002, 181, 'Jamalo-Nenets']),
            $this->addPupilKeys([3003, 181, 'Jaroslavl']),
            $this->addPupilKeys([3004, 181, 'Jevrej']),
            $this->addPupilKeys([3005, 181, 'Kabardino-Balkarija']),
            $this->addPupilKeys([3006, 181, 'Kaliningrad']),
            $this->addPupilKeys([3007, 181, 'Kalmykija']),
            $this->addPupilKeys([3008, 181, 'Kaluga']),
            $this->addPupilKeys([3009, 181, 'Kamchatka']),
            $this->addPupilKeys([3010, 181, 'Karachaj-Cherkessija']),
            $this->addPupilKeys([3011, 181, 'Karelija']),
            $this->addPupilKeys([3012, 181, 'Kemerovo']),
            $this->addPupilKeys([3013, 181, 'Khabarovskiy Kray']),
            $this->addPupilKeys([3014, 181, 'Kirov']),
            $this->addPupilKeys([3015, 181, 'Komi']),
            $this->addPupilKeys([3016, 181, 'Komi-Permjakija']),
            $this->addPupilKeys([3017, 181, 'Korjakija']),
            $this->addPupilKeys([3018, 181, 'Kostroma']),
            $this->addPupilKeys([3019, 181, 'Krasnodar']),
            $this->addPupilKeys([3020, 181, 'Krasnojarsk']),
            $this->addPupilKeys([3021, 181, 'Krasnoyarskiy Kray']),
            $this->addPupilKeys([3022, 181, 'Kurgan']),
            $this->addPupilKeys([3023, 181, 'Kursk']),
            $this->addPupilKeys([3024, 181, 'Leningrad']),
            $this->addPupilKeys([3025, 181, 'Lipeck']),
            $this->addPupilKeys([3026, 181, 'Magadan']),
            $this->addPupilKeys([3027, 181, 'Marij El']),
            $this->addPupilKeys([3028, 181, 'Mordovija']),
            $this->addPupilKeys([3029, 181, 'Moscow']),
            $this->addPupilKeys([3030, 181, 'Moskovskaja Oblast']),
            $this->addPupilKeys([3031, 181, 'Moskovskaya Oblast']),
            $this->addPupilKeys([3032, 181, 'Moskva']),
            $this->addPupilKeys([3033, 181, 'Murmansk']),
            $this->addPupilKeys([3034, 181, 'Nenets']),
            $this->addPupilKeys([3035, 181, 'Nizhnij Novgorod']),
            $this->addPupilKeys([3036, 181, 'Novgorod']),
            $this->addPupilKeys([3037, 181, 'Novokusnezk']),
            $this->addPupilKeys([3038, 181, 'Novosibirsk']),
            $this->addPupilKeys([3039, 181, 'Omsk']),
            $this->addPupilKeys([3040, 181, 'Orenburg']),
            $this->addPupilKeys([3041, 181, 'Orjol']),
            $this->addPupilKeys([3042, 181, 'Penza']),
            $this->addPupilKeys([3043, 181, 'Perm']),
            $this->addPupilKeys([3044, 181, 'Primorje']),
            $this->addPupilKeys([3045, 181, 'Pskov']),
            $this->addPupilKeys([3046, 181, 'Pskovskaya Oblast']),
            $this->addPupilKeys([3047, 181, 'Rjazan']),
            $this->addPupilKeys([3048, 181, 'Rostov']),
            $this->addPupilKeys([3049, 181, 'Saha']),
            $this->addPupilKeys([3050, 181, 'Sahalin']),
            $this->addPupilKeys([3051, 181, 'Samara']),
            $this->addPupilKeys([3052, 181, 'Samarskaya']),
            $this->addPupilKeys([3053, 181, 'Sankt-Peterburg']),
            $this->addPupilKeys([3054, 181, 'Saratov']),
            $this->addPupilKeys([3055, 181, 'Smolensk']),
            $this->addPupilKeys([3056, 181, 'Stavropol']),
            $this->addPupilKeys([3057, 181, 'Sverdlovsk']),
            $this->addPupilKeys([3058, 181, 'Tajmyrija']),
            $this->addPupilKeys([3059, 181, 'Tambov']),
            $this->addPupilKeys([3060, 181, 'Tatarstan']),
            $this->addPupilKeys([3061, 181, 'Tjumen']),
            $this->addPupilKeys([3062, 181, 'Tomsk']),
            $this->addPupilKeys([3063, 181, 'Tula']),
            $this->addPupilKeys([3064, 181, 'Tver']),
            $this->addPupilKeys([3065, 181, 'Tyva']),
            $this->addPupilKeys([3066, 181, 'Udmurtija']),
            $this->addPupilKeys([3067, 181, 'Uljanovsk']),
            $this->addPupilKeys([3068, 181, 'Ulyanovskaya Oblast']),
            $this->addPupilKeys([3069, 181, 'Ust-Orda']),
            $this->addPupilKeys([3070, 181, 'Vladimir']),
            $this->addPupilKeys([3071, 181, 'Volgograd']),
            $this->addPupilKeys([3072, 181, 'Vologda']),
            $this->addPupilKeys([3073, 181, 'Voronezh']),
            $this->addPupilKeys([3074, 182, 'Butare']),
            $this->addPupilKeys([3075, 182, 'Byumba']),
            $this->addPupilKeys([3076, 182, 'Cyangugu']),
            $this->addPupilKeys([3077, 182, 'Gikongoro']),
            $this->addPupilKeys([3078, 182, 'Gisenyi']),
            $this->addPupilKeys([3079, 182, 'Gitarama']),
            $this->addPupilKeys([3080, 182, 'Kibungo']),
            $this->addPupilKeys([3081, 182, 'Kibuye']),
            $this->addPupilKeys([3082, 182, 'Kigali-ngali']),
            $this->addPupilKeys([3083, 182, 'Ruhengeri']),
            $this->addPupilKeys([3084, 183, 'Ascension']),
            $this->addPupilKeys([3085, 183, 'Gough Island']),
            $this->addPupilKeys([3086, 183, 'Saint Helena']),
            $this->addPupilKeys([3087, 183, 'Tristan da Cunha']),
            $this->addPupilKeys([3088, 184, 'Christ Church Nichola Town']),
            $this->addPupilKeys([3089, 184, 'Saint Anne Sandy Point']),
            $this->addPupilKeys([3090, 184, 'Saint George Basseterre']),
            $this->addPupilKeys([3091, 184, 'Saint George Gingerland']),
            $this->addPupilKeys([3092, 184, 'Saint James Windward']),
            $this->addPupilKeys([3093, 184, 'Saint John Capesterre']),
            $this->addPupilKeys([3094, 184, 'Saint John Figtree']),
            $this->addPupilKeys([3095, 184, 'Saint Mary Cayon']),
            $this->addPupilKeys([3096, 184, 'Saint Paul Capesterre']),
            $this->addPupilKeys([3097, 184, 'Saint Paul Charlestown']),
            $this->addPupilKeys([3098, 184, 'Saint Peter Basseterre']),
            $this->addPupilKeys([3099, 184, 'Saint Thomas Lowland']),
            $this->addPupilKeys([3100, 184, 'Saint Thomas Middle Island']),
            $this->addPupilKeys([3101, 184, 'Trinity Palmetto Point']),
            $this->addPupilKeys([3102, 185, 'Anse-la-Raye']),
            $this->addPupilKeys([3103, 185, 'Canaries']),
            $this->addPupilKeys([3104, 185, 'Castries']),
            $this->addPupilKeys([3105, 185, 'Choiseul']),
            $this->addPupilKeys([3106, 185, 'Dennery']),
            $this->addPupilKeys([3107, 185, 'Gros Inlet']),
            $this->addPupilKeys([3108, 185, 'Laborie']),
            $this->addPupilKeys([3109, 185, 'Micoud']),
            $this->addPupilKeys([3110, 185, 'Soufriere']),
            $this->addPupilKeys([3111, 185, 'Vieux Fort']),
            $this->addPupilKeys([3112, 186, 'Miquelon-Langlade']),
            $this->addPupilKeys([3113, 186, 'Saint-Pierre']),
            $this->addPupilKeys([3114, 187, 'Charlotte']),
            $this->addPupilKeys([3115, 187, 'Grenadines']),
            $this->addPupilKeys([3116, 187, 'Saint Andrew']),
            $this->addPupilKeys([3117, 187, 'Saint David']),
            $this->addPupilKeys([3118, 187, 'Saint George']),
            $this->addPupilKeys([3119, 187, 'Saint Patrick']),
            $this->addPupilKeys([3120, 188, 'A\'ana']),
            $this->addPupilKeys([3121, 188, 'Aiga-i-le-Tai']),
            $this->addPupilKeys([3122, 188, 'Atua']),
            $this->addPupilKeys([3123, 188, 'Fa\'asaleleaga']),
            $this->addPupilKeys([3124, 188, 'Gaga\'emauga']),
            $this->addPupilKeys([3125, 188, 'Gagaifomauga']),
            $this->addPupilKeys([3126, 188, 'Palauli']),
            $this->addPupilKeys([3127, 188, 'Satupa\'itea']),
            $this->addPupilKeys([3128, 188, 'Tuamasaga']),
            $this->addPupilKeys([3129, 188, 'Va\'a-o-Fonoti']),
            $this->addPupilKeys([3130, 188, 'Vaisigano']),
            $this->addPupilKeys([3131, 189, 'Acquaviva']),
            $this->addPupilKeys([3132, 189, 'Borgo Maggiore']),
            $this->addPupilKeys([3133, 189, 'Chiesanuova']),
            $this->addPupilKeys([3134, 189, 'Domagnano']),
            $this->addPupilKeys([3135, 189, 'Faetano']),
            $this->addPupilKeys([3136, 189, 'Fiorentino']),
            $this->addPupilKeys([3137, 189, 'Montegiardino']),
            $this->addPupilKeys([3138, 189, 'San Marino']),
            $this->addPupilKeys([3139, 189, 'Serravalle']),
            $this->addPupilKeys([3140, 190, 'Agua Grande']),
            $this->addPupilKeys([3141, 190, 'Cantagalo']),
            $this->addPupilKeys([3142, 190, 'Lemba']),
            $this->addPupilKeys([3143, 190, 'Lobata']),
            $this->addPupilKeys([3144, 190, 'Me-Zochi']),
            $this->addPupilKeys([3145, 190, 'Pague']),
            $this->addPupilKeys([3146, 191, 'Al Khobar']),
            $this->addPupilKeys([3147, 191, 'Aseer']),
            $this->addPupilKeys([3148, 191, 'Ash Sharqiyah']),
            $this->addPupilKeys([3149, 191, 'Asir']),
            $this->addPupilKeys([3150, 191, 'Central Province']),
            $this->addPupilKeys([3151, 191, 'Eastern Province']),
            $this->addPupilKeys([3152, 191, 'Ha\'il']),
            $this->addPupilKeys([3153, 191, 'Jawf']),
            $this->addPupilKeys([3154, 191, 'Jizan']),
            $this->addPupilKeys([3155, 191, 'Makkah']),
            $this->addPupilKeys([3156, 191, 'Najran']),
            $this->addPupilKeys([3157, 191, 'Qasim']),
            $this->addPupilKeys([3158, 191, 'Tabuk']),
            $this->addPupilKeys([3159, 191, 'Western Province']),
            $this->addPupilKeys([3160, 191, 'al-Bahah']),
            $this->addPupilKeys([3161, 191, 'al-Hudud-ash-Shamaliyah']),
            $this->addPupilKeys([3162, 191, 'al-Madinah']),
            $this->addPupilKeys([3163, 191, 'ar-Riyad']),
            $this->addPupilKeys([3164, 192, 'Dakar']),
            $this->addPupilKeys([3165, 192, 'Diourbel']),
            $this->addPupilKeys([3166, 192, 'Fatick']),
            $this->addPupilKeys([3167, 192, 'Kaolack']),
            $this->addPupilKeys([3168, 192, 'Kolda']),
            $this->addPupilKeys([3169, 192, 'Louga']),
            $this->addPupilKeys([3170, 192, 'Saint-Louis']),
            $this->addPupilKeys([3171, 192, 'Tambacounda']),
            $this->addPupilKeys([3172, 192, 'Thies']),
            $this->addPupilKeys([3173, 192, 'Ziguinchor']),
            $this->addPupilKeys([3174, 193, 'Central Serbia']),
            $this->addPupilKeys([3175, 193, 'Kosovo and Metohija']),
            $this->addPupilKeys([3176, 193, 'Vojvodina']),
            $this->addPupilKeys([3177, 194, 'Anse Boileau']),
            $this->addPupilKeys([3178, 194, 'Anse Royale']),
            $this->addPupilKeys([3179, 194, 'Cascade']),
            $this->addPupilKeys([3180, 194, 'Takamaka']),
            $this->addPupilKeys([3181, 194, 'Victoria']),
            $this->addPupilKeys([3182, 195, 'Eastern']),
            $this->addPupilKeys([3183, 195, 'Northern']),
            $this->addPupilKeys([3184, 195, 'Southern']),
            $this->addPupilKeys([3185, 195, 'Western']),
            $this->addPupilKeys([3186, 196, 'Singapore']),
            $this->addPupilKeys([3187, 197, 'Banskobystricky']),
            $this->addPupilKeys([3188, 197, 'Bratislavsky']),
            $this->addPupilKeys([3189, 197, 'Kosicky']),
            $this->addPupilKeys([3190, 197, 'Nitriansky']),
            $this->addPupilKeys([3191, 197, 'Presovsky']),
            $this->addPupilKeys([3192, 197, 'Trenciansky']),
            $this->addPupilKeys([3193, 197, 'Trnavsky']),
            $this->addPupilKeys([3194, 197, 'Zilinsky']),
            $this->addPupilKeys([3195, 198, 'Benedikt']),
            $this->addPupilKeys([3196, 198, 'Gorenjska']),
            $this->addPupilKeys([3197, 198, 'Gorishka']),
            $this->addPupilKeys([3198, 198, 'Jugovzhodna Slovenija']),
            $this->addPupilKeys([3199, 198, 'Koroshka']),
            $this->addPupilKeys([3200, 198, 'Notranjsko-krashka']),
            $this->addPupilKeys([3201, 198, 'Obalno-krashka']),
            $this->addPupilKeys([3202, 198, 'Obcina Domzale']),
            $this->addPupilKeys([3203, 198, 'Obcina Vitanje']),
            $this->addPupilKeys([3204, 198, 'Osrednjeslovenska']),
            $this->addPupilKeys([3205, 198, 'Podravska']),
            $this->addPupilKeys([3206, 198, 'Pomurska']),
            $this->addPupilKeys([3207, 198, 'Savinjska']),
            $this->addPupilKeys([3208, 198, 'Slovenian Littoral']),
            $this->addPupilKeys([3209, 198, 'Spodnjeposavska']),
            $this->addPupilKeys([3210, 198, 'Zasavska']),
            $this->addPupilKeys([3211, 199, 'Pitcairn']),
            $this->addPupilKeys([3212, 200, 'Central']),
            $this->addPupilKeys([3213, 200, 'Choiseul']),
            $this->addPupilKeys([3214, 200, 'Guadalcanal']),
            $this->addPupilKeys([3215, 200, 'Isabel']),
            $this->addPupilKeys([3216, 200, 'Makira and Ulawa']),
            $this->addPupilKeys([3217, 200, 'Malaita']),
            $this->addPupilKeys([3218, 200, 'Rennell and Bellona']),
            $this->addPupilKeys([3219, 200, 'Temotu']),
            $this->addPupilKeys([3220, 200, 'Western']),
            $this->addPupilKeys([3221, 201, 'Awdal']),
            $this->addPupilKeys([3222, 201, 'Bakol']),
            $this->addPupilKeys([3223, 201, 'Banadir']),
            $this->addPupilKeys([3224, 201, 'Bari']),
            $this->addPupilKeys([3225, 201, 'Bay']),
            $this->addPupilKeys([3226, 201, 'Galgudug']),
            $this->addPupilKeys([3227, 201, 'Gedo']),
            $this->addPupilKeys([3228, 201, 'Hiran']),
            $this->addPupilKeys([3229, 201, 'Jubbada Hose']),
            $this->addPupilKeys([3230, 201, 'Jubbadha Dexe']),
            $this->addPupilKeys([3231, 201, 'Mudug']),
            $this->addPupilKeys([3232, 201, 'Nugal']),
            $this->addPupilKeys([3233, 201, 'Sanag']),
            $this->addPupilKeys([3234, 201, 'Shabellaha Dhexe']),
            $this->addPupilKeys([3235, 201, 'Shabellaha Hose']),
            $this->addPupilKeys([3236, 201, 'Togdher']),
            $this->addPupilKeys([3237, 201, 'Woqoyi Galbed']),
            $this->addPupilKeys([3238, 202, 'Eastern Cape']),
            $this->addPupilKeys([3239, 202, 'Free State']),
            $this->addPupilKeys([3240, 202, 'Gauteng']),
            $this->addPupilKeys([3241, 202, 'Kempton Park']),
            $this->addPupilKeys([3242, 202, 'Kramerville']),
            $this->addPupilKeys([3243, 202, 'KwaZulu Natal']),
            $this->addPupilKeys([3244, 202, 'Limpopo']),
            $this->addPupilKeys([3245, 202, 'Mpumalanga']),
            $this->addPupilKeys([3246, 202, 'North West']),
            $this->addPupilKeys([3247, 202, 'Northern Cape']),
            $this->addPupilKeys([3248, 202, 'Parow']),
            $this->addPupilKeys([3249, 202, 'Table View']),
            $this->addPupilKeys([3250, 202, 'Umtentweni']),
            $this->addPupilKeys([3251, 202, 'Western Cape']),
            $this->addPupilKeys([3252, 203, 'South Georgia']),
            $this->addPupilKeys([3253, 204, 'Central Equatoria']),
            $this->addPupilKeys([3254, 205, 'A Coruna']),
            $this->addPupilKeys([3255, 205, 'Alacant']),
            $this->addPupilKeys([3256, 205, 'Alava']),
            $this->addPupilKeys([3257, 205, 'Albacete']),
            $this->addPupilKeys([3258, 205, 'Almeria']),
            $this->addPupilKeys([3259, 205, 'Andalucia']),
            $this->addPupilKeys([3260, 205, 'Asturias']),
            $this->addPupilKeys([3261, 205, 'Avila']),
            $this->addPupilKeys([3262, 205, 'Badajoz']),
            $this->addPupilKeys([3263, 205, 'Balears']),
            $this->addPupilKeys([3264, 205, 'Barcelona']),
            $this->addPupilKeys([3265, 205, 'Bertamirans']),
            $this->addPupilKeys([3266, 205, 'Biscay']),
            $this->addPupilKeys([3267, 205, 'Burgos']),
            $this->addPupilKeys([3268, 205, 'Caceres']),
            $this->addPupilKeys([3269, 205, 'Cadiz']),
            $this->addPupilKeys([3270, 205, 'Cantabria']),
            $this->addPupilKeys([3271, 205, 'Castello']),
            $this->addPupilKeys([3272, 205, 'Catalunya']),
            $this->addPupilKeys([3273, 205, 'Ceuta']),
            $this->addPupilKeys([3274, 205, 'Ciudad Real']),
            $this->addPupilKeys([3275, 205, 'Comunidad Autonoma de Canarias']),
            $this->addPupilKeys([3276, 205, 'Comunidad Autonoma de Cataluna']),
            $this->addPupilKeys([3277, 205, 'Comunidad Autonoma de Galicia']),
            $this->addPupilKeys([3278, 205, 'Comunidad Autonoma de las Isla']),
            $this->addPupilKeys([3279, 205, 'Comunidad Autonoma del Princip']),
            $this->addPupilKeys([3280, 205, 'Comunidad Valenciana']),
            $this->addPupilKeys([3281, 205, 'Cordoba']),
            $this->addPupilKeys([3282, 205, 'Cuenca']),
            $this->addPupilKeys([3283, 205, 'Gipuzkoa']),
            $this->addPupilKeys([3284, 205, 'Girona']),
            $this->addPupilKeys([3285, 205, 'Granada']),
            $this->addPupilKeys([3286, 205, 'Guadalajara']),
            $this->addPupilKeys([3287, 205, 'Guipuzcoa']),
            $this->addPupilKeys([3288, 205, 'Huelva']),
            $this->addPupilKeys([3289, 205, 'Huesca']),
            $this->addPupilKeys([3290, 205, 'Jaen']),
            $this->addPupilKeys([3291, 205, 'La Rioja']),
            $this->addPupilKeys([3292, 205, 'Las Palmas']),
            $this->addPupilKeys([3293, 205, 'Leon']),
            $this->addPupilKeys([3294, 205, 'Lerida']),
            $this->addPupilKeys([3295, 205, 'Lleida']),
            $this->addPupilKeys([3296, 205, 'Lugo']),
            $this->addPupilKeys([3297, 205, 'Madrid']),
            $this->addPupilKeys([3298, 205, 'Malaga']),
            $this->addPupilKeys([3299, 205, 'Melilla']),
            $this->addPupilKeys([3300, 205, 'Murcia']),
            $this->addPupilKeys([3301, 205, 'Navarra']),
            $this->addPupilKeys([3302, 205, 'Ourense']),
            $this->addPupilKeys([3303, 205, 'Pais Vasco']),
            $this->addPupilKeys([3304, 205, 'Palencia']),
            $this->addPupilKeys([3305, 205, 'Pontevedra']),
            $this->addPupilKeys([3306, 205, 'Salamanca']),
            $this->addPupilKeys([3307, 205, 'Santa Cruz de Tenerife']),
            $this->addPupilKeys([3308, 205, 'Segovia']),
            $this->addPupilKeys([3309, 205, 'Sevilla']),
            $this->addPupilKeys([3310, 205, 'Soria']),
            $this->addPupilKeys([3311, 205, 'Tarragona']),
            $this->addPupilKeys([3312, 205, 'Tenerife']),
            $this->addPupilKeys([3313, 205, 'Teruel']),
            $this->addPupilKeys([3314, 205, 'Toledo']),
            $this->addPupilKeys([3315, 205, 'Valencia']),
            $this->addPupilKeys([3316, 205, 'Valladolid']),
            $this->addPupilKeys([3317, 205, 'Vizcaya']),
            $this->addPupilKeys([3318, 205, 'Zamora']),
            $this->addPupilKeys([3319, 205, 'Zaragoza']),
            $this->addPupilKeys([3320, 206, 'Amparai']),
            $this->addPupilKeys([3321, 206, 'Anuradhapuraya']),
            $this->addPupilKeys([3322, 206, 'Badulla']),
            $this->addPupilKeys([3323, 206, 'Boralesgamuwa']),
            $this->addPupilKeys([3324, 206, 'Colombo']),
            $this->addPupilKeys([3325, 206, 'Galla']),
            $this->addPupilKeys([3326, 206, 'Gampaha']),
            $this->addPupilKeys([3327, 206, 'Hambantota']),
            $this->addPupilKeys([3328, 206, 'Kalatura']),
            $this->addPupilKeys([3329, 206, 'Kegalla']),
            $this->addPupilKeys([3330, 206, 'Kilinochchi']),
            $this->addPupilKeys([3331, 206, 'Kurunegala']),
            $this->addPupilKeys([3332, 206, 'Madakalpuwa']),
            $this->addPupilKeys([3333, 206, 'Maha Nuwara']),
            $this->addPupilKeys([3334, 206, 'Malwana']),
            $this->addPupilKeys([3335, 206, 'Mannarama']),
            $this->addPupilKeys([3336, 206, 'Matale']),
            $this->addPupilKeys([3337, 206, 'Matara']),
            $this->addPupilKeys([3338, 206, 'Monaragala']),
            $this->addPupilKeys([3339, 206, 'Mullaitivu']),
            $this->addPupilKeys([3340, 206, 'North Eastern Province']),
            $this->addPupilKeys([3341, 206, 'North Western Province']),
            $this->addPupilKeys([3342, 206, 'Nuwara Eliya']),
            $this->addPupilKeys([3343, 206, 'Polonnaruwa']),
            $this->addPupilKeys([3344, 206, 'Puttalama']),
            $this->addPupilKeys([3345, 206, 'Ratnapuraya']),
            $this->addPupilKeys([3346, 206, 'Southern Province']),
            $this->addPupilKeys([3347, 206, 'Tirikunamalaya']),
            $this->addPupilKeys([3348, 206, 'Tuscany']),
            $this->addPupilKeys([3349, 206, 'Vavuniyawa']),
            $this->addPupilKeys([3350, 206, 'Western Province']),
            $this->addPupilKeys([3351, 206, 'Yapanaya']),
            $this->addPupilKeys([3352, 206, 'kadawatha']),
            $this->addPupilKeys([3353, 207, 'A\'ali-an-Nil']),
            $this->addPupilKeys([3354, 207, 'Bahr-al-Jabal']),
            $this->addPupilKeys([3355, 207, 'Central Equatoria']),
            $this->addPupilKeys([3356, 207, 'Gharb Bahr-al-Ghazal']),
            $this->addPupilKeys([3357, 207, 'Gharb Darfur']),
            $this->addPupilKeys([3358, 207, 'Gharb Kurdufan']),
            $this->addPupilKeys([3359, 207, 'Gharb-al-Istiwa\'iyah']),
            $this->addPupilKeys([3360, 207, 'Janub Darfur']),
            $this->addPupilKeys([3361, 207, 'Janub Kurdufan']),
            $this->addPupilKeys([3362, 207, 'Junqali']),
            $this->addPupilKeys([3363, 207, 'Kassala']),
            $this->addPupilKeys([3364, 207, 'Nahr-an-Nil']),
            $this->addPupilKeys([3365, 207, 'Shamal Bahr-al-Ghazal']),
            $this->addPupilKeys([3366, 207, 'Shamal Darfur']),
            $this->addPupilKeys([3367, 207, 'Shamal Kurdufan']),
            $this->addPupilKeys([3368, 207, 'Sharq-al-Istiwa\'iyah']),
            $this->addPupilKeys([3369, 207, 'Sinnar']),
            $this->addPupilKeys([3370, 207, 'Warab']),
            $this->addPupilKeys([3371, 207, 'Wilayat al Khartum']),
            $this->addPupilKeys([3372, 207, 'al-Bahr-al-Ahmar']),
            $this->addPupilKeys([3373, 207, 'al-Buhayrat']),
            $this->addPupilKeys([3374, 207, 'al-Jazirah']),
            $this->addPupilKeys([3375, 207, 'al-Khartum']),
            $this->addPupilKeys([3376, 207, 'al-Qadarif']),
            $this->addPupilKeys([3377, 207, 'al-Wahdah']),
            $this->addPupilKeys([3378, 207, 'an-Nil-al-Abyad']),
            $this->addPupilKeys([3379, 207, 'an-Nil-al-Azraq']),
            $this->addPupilKeys([3380, 207, 'ash-Shamaliyah']),
            $this->addPupilKeys([3381, 208, 'Brokopondo']),
            $this->addPupilKeys([3382, 208, 'Commewijne']),
            $this->addPupilKeys([3383, 208, 'Coronie']),
            $this->addPupilKeys([3384, 208, 'Marowijne']),
            $this->addPupilKeys([3385, 208, 'Nickerie']),
            $this->addPupilKeys([3386, 208, 'Para']),
            $this->addPupilKeys([3387, 208, 'Paramaribo']),
            $this->addPupilKeys([3388, 208, 'Saramacca']),
            $this->addPupilKeys([3389, 208, 'Wanica']),
            $this->addPupilKeys([3390, 209, 'Svalbard']),
            $this->addPupilKeys([3391, 210, 'Hhohho']),
            $this->addPupilKeys([3392, 210, 'Lubombo']),
            $this->addPupilKeys([3393, 210, 'Manzini']),
            $this->addPupilKeys([3394, 210, 'Shiselweni']),
            $this->addPupilKeys([3395, 211, 'Alvsborgs Lan']),
            $this->addPupilKeys([3396, 211, 'Angermanland']),
            $this->addPupilKeys([3397, 211, 'Blekinge']),
            $this->addPupilKeys([3398, 211, 'Bohuslan']),
            $this->addPupilKeys([3399, 211, 'Dalarna']),
            $this->addPupilKeys([3400, 211, 'Gavleborg']),
            $this->addPupilKeys([3401, 211, 'Gaza']),
            $this->addPupilKeys([3402, 211, 'Gotland']),
            $this->addPupilKeys([3403, 211, 'Halland']),
            $this->addPupilKeys([3404, 211, 'Jamtland']),
            $this->addPupilKeys([3405, 211, 'Jonkoping']),
            $this->addPupilKeys([3406, 211, 'Kalmar']),
            $this->addPupilKeys([3407, 211, 'Kristianstads']),
            $this->addPupilKeys([3408, 211, 'Kronoberg']),
            $this->addPupilKeys([3409, 211, 'Norrbotten']),
            $this->addPupilKeys([3410, 211, 'Orebro']),
            $this->addPupilKeys([3411, 211, 'Ostergotland']),
            $this->addPupilKeys([3412, 211, 'Saltsjo-Boo']),
            $this->addPupilKeys([3413, 211, 'Skane']),
            $this->addPupilKeys([3414, 211, 'Smaland']),
            $this->addPupilKeys([3415, 211, 'Sodermanland']),
            $this->addPupilKeys([3416, 211, 'Stockholm']),
            $this->addPupilKeys([3417, 211, 'Uppsala']),
            $this->addPupilKeys([3418, 211, 'Varmland']),
            $this->addPupilKeys([3419, 211, 'Vasterbotten']),
            $this->addPupilKeys([3420, 211, 'Vastergotland']),
            $this->addPupilKeys([3421, 211, 'Vasternorrland']),
            $this->addPupilKeys([3422, 211, 'Vastmanland']),
            $this->addPupilKeys([3423, 211, 'Vastra Gotaland']),
            $this->addPupilKeys([3424, 212, 'Aargau']),
            $this->addPupilKeys([3425, 212, 'Appenzell Inner-Rhoden']),
            $this->addPupilKeys([3426, 212, 'Appenzell-Ausser Rhoden']),
            $this->addPupilKeys([3427, 212, 'Basel-Landschaft']),
            $this->addPupilKeys([3428, 212, 'Basel-Stadt']),
            $this->addPupilKeys([3429, 212, 'Bern']),
            $this->addPupilKeys([3430, 212, 'Canton Ticino']),
            $this->addPupilKeys([3431, 212, 'Fribourg']),
            $this->addPupilKeys([3432, 212, 'Geneve']),
            $this->addPupilKeys([3433, 212, 'Glarus']),
            $this->addPupilKeys([3434, 212, 'Graubunden']),
            $this->addPupilKeys([3435, 212, 'Heerbrugg']),
            $this->addPupilKeys([3436, 212, 'Jura']),
            $this->addPupilKeys([3437, 212, 'Kanton Aargau']),
            $this->addPupilKeys([3438, 212, 'Luzern']),
            $this->addPupilKeys([3439, 212, 'Morbio Inferiore']),
            $this->addPupilKeys([3440, 212, 'Muhen']),
            $this->addPupilKeys([3441, 212, 'Neuchatel']),
            $this->addPupilKeys([3442, 212, 'Nidwalden']),
            $this->addPupilKeys([3443, 212, 'Obwalden']),
            $this->addPupilKeys([3444, 212, 'Sankt Gallen']),
            $this->addPupilKeys([3445, 212, 'Schaffhausen']),
            $this->addPupilKeys([3446, 212, 'Schwyz']),
            $this->addPupilKeys([3447, 212, 'Solothurn']),
            $this->addPupilKeys([3448, 212, 'Thurgau']),
            $this->addPupilKeys([3449, 212, 'Ticino']),
            $this->addPupilKeys([3450, 212, 'Uri']),
            $this->addPupilKeys([3451, 212, 'Valais']),
            $this->addPupilKeys([3452, 212, 'Vaud']),
            $this->addPupilKeys([3453, 212, 'Vauffelin']),
            $this->addPupilKeys([3454, 212, 'Zug']),
            $this->addPupilKeys([3455, 212, 'Zurich']),
            $this->addPupilKeys([3456, 213, 'Aleppo']),
            $this->addPupilKeys([3457, 213, 'Dar\'a']),
            $this->addPupilKeys([3458, 213, 'Dayr-az-Zawr']),
            $this->addPupilKeys([3459, 213, 'Dimashq']),
            $this->addPupilKeys([3460, 213, 'Halab']),
            $this->addPupilKeys([3461, 213, 'Hamah']),
            $this->addPupilKeys([3462, 213, 'Hims']),
            $this->addPupilKeys([3463, 213, 'Idlib']),
            $this->addPupilKeys([3464, 213, 'Madinat Dimashq']),
            $this->addPupilKeys([3465, 213, 'Tartus']),
            $this->addPupilKeys([3466, 213, 'al-Hasakah']),
            $this->addPupilKeys([3467, 213, 'al-Ladhiqiyah']),
            $this->addPupilKeys([3468, 213, 'al-Qunaytirah']),
            $this->addPupilKeys([3469, 213, 'ar-Raqqah']),
            $this->addPupilKeys([3470, 213, 'as-Suwayda']),
            $this->addPupilKeys([3471, 214, 'Changhwa']),
            $this->addPupilKeys([3472, 214, 'Chiayi Hsien']),
            $this->addPupilKeys([3473, 214, 'Chiayi Shih']),
            $this->addPupilKeys([3474, 214, 'Eastern Taipei']),
            $this->addPupilKeys([3475, 214, 'Hsinchu Hsien']),
            $this->addPupilKeys([3476, 214, 'Hsinchu Shih']),
            $this->addPupilKeys([3477, 214, 'Hualien']),
            $this->addPupilKeys([3478, 214, 'Ilan']),
            $this->addPupilKeys([3479, 214, 'Kaohsiung Hsien']),
            $this->addPupilKeys([3480, 214, 'Kaohsiung Shih']),
            $this->addPupilKeys([3481, 214, 'Keelung Shih']),
            $this->addPupilKeys([3482, 214, 'Kinmen']),
            $this->addPupilKeys([3483, 214, 'Miaoli']),
            $this->addPupilKeys([3484, 214, 'Nantou']),
            $this->addPupilKeys([3485, 214, 'Northern Taiwan']),
            $this->addPupilKeys([3486, 214, 'Penghu']),
            $this->addPupilKeys([3487, 214, 'Pingtung']),
            $this->addPupilKeys([3488, 214, 'Taichung']),
            $this->addPupilKeys([3489, 214, 'Taichung Hsien']),
            $this->addPupilKeys([3490, 214, 'Taichung Shih']),
            $this->addPupilKeys([3491, 214, 'Tainan Hsien']),
            $this->addPupilKeys([3492, 214, 'Tainan Shih']),
            $this->addPupilKeys([3493, 214, 'Taipei Hsien']),
            $this->addPupilKeys([3494, 214, 'Taipei Shih / Taipei Hsien']),
            $this->addPupilKeys([3495, 214, 'Taitung']),
            $this->addPupilKeys([3496, 214, 'Taoyuan']),
            $this->addPupilKeys([3497, 214, 'Yilan']),
            $this->addPupilKeys([3498, 214, 'Yun-Lin Hsien']),
            $this->addPupilKeys([3499, 214, 'Yunlin']),
            $this->addPupilKeys([3500, 215, 'Dushanbe']),
            $this->addPupilKeys([3501, 215, 'Gorno-Badakhshan']),
            $this->addPupilKeys([3502, 215, 'Karotegin']),
            $this->addPupilKeys([3503, 215, 'Khatlon']),
            $this->addPupilKeys([3504, 215, 'Sughd']),
            $this->addPupilKeys([3505, 216, 'Arusha']),
            $this->addPupilKeys([3506, 216, 'Dar es Salaam']),
            $this->addPupilKeys([3507, 216, 'Dodoma']),
            $this->addPupilKeys([3508, 216, 'Iringa']),
            $this->addPupilKeys([3509, 216, 'Kagera']),
            $this->addPupilKeys([3510, 216, 'Kigoma']),
            $this->addPupilKeys([3511, 216, 'Kilimanjaro']),
            $this->addPupilKeys([3512, 216, 'Lindi']),
            $this->addPupilKeys([3513, 216, 'Mara']),
            $this->addPupilKeys([3514, 216, 'Mbeya']),
            $this->addPupilKeys([3515, 216, 'Morogoro']),
            $this->addPupilKeys([3516, 216, 'Mtwara']),
            $this->addPupilKeys([3517, 216, 'Mwanza']),
            $this->addPupilKeys([3518, 216, 'Pwani']),
            $this->addPupilKeys([3519, 216, 'Rukwa']),
            $this->addPupilKeys([3520, 216, 'Ruvuma']),
            $this->addPupilKeys([3521, 216, 'Shinyanga']),
            $this->addPupilKeys([3522, 216, 'Singida']),
            $this->addPupilKeys([3523, 216, 'Tabora']),
            $this->addPupilKeys([3524, 216, 'Tanga']),
            $this->addPupilKeys([3525, 216, 'Zanzibar and Pemba']),
            $this->addPupilKeys([3526, 217, 'Amnat Charoen']),
            $this->addPupilKeys([3527, 217, 'Ang Thong']),
            $this->addPupilKeys([3528, 217, 'Bangkok']),
            $this->addPupilKeys([3529, 217, 'Buri Ram']),
            $this->addPupilKeys([3530, 217, 'Chachoengsao']),
            $this->addPupilKeys([3531, 217, 'Chai Nat']),
            $this->addPupilKeys([3532, 217, 'Chaiyaphum']),
            $this->addPupilKeys([3533, 217, 'Changwat Chaiyaphum']),
            $this->addPupilKeys([3534, 217, 'Chanthaburi']),
            $this->addPupilKeys([3535, 217, 'Chiang Mai']),
            $this->addPupilKeys([3536, 217, 'Chiang Rai']),
            $this->addPupilKeys([3537, 217, 'Chon Buri']),
            $this->addPupilKeys([3538, 217, 'Chumphon']),
            $this->addPupilKeys([3539, 217, 'Kalasin']),
            $this->addPupilKeys([3540, 217, 'Kamphaeng Phet']),
            $this->addPupilKeys([3541, 217, 'Kanchanaburi']),
            $this->addPupilKeys([3542, 217, 'Khon Kaen']),
            $this->addPupilKeys([3543, 217, 'Krabi']),
            $this->addPupilKeys([3544, 217, 'Krung Thep']),
            $this->addPupilKeys([3545, 217, 'Lampang']),
            $this->addPupilKeys([3546, 217, 'Lamphun']),
            $this->addPupilKeys([3547, 217, 'Loei']),
            $this->addPupilKeys([3548, 217, 'Lop Buri']),
            $this->addPupilKeys([3549, 217, 'Mae Hong Son']),
            $this->addPupilKeys([3550, 217, 'Maha Sarakham']),
            $this->addPupilKeys([3551, 217, 'Mukdahan']),
            $this->addPupilKeys([3552, 217, 'Nakhon Nayok']),
            $this->addPupilKeys([3553, 217, 'Nakhon Pathom']),
            $this->addPupilKeys([3554, 217, 'Nakhon Phanom']),
            $this->addPupilKeys([3555, 217, 'Nakhon Ratchasima']),
            $this->addPupilKeys([3556, 217, 'Nakhon Sawan']),
            $this->addPupilKeys([3557, 217, 'Nakhon Si Thammarat']),
            $this->addPupilKeys([3558, 217, 'Nan']),
            $this->addPupilKeys([3559, 217, 'Narathiwat']),
            $this->addPupilKeys([3560, 217, 'Nong Bua Lam Phu']),
            $this->addPupilKeys([3561, 217, 'Nong Khai']),
            $this->addPupilKeys([3562, 217, 'Nonthaburi']),
            $this->addPupilKeys([3563, 217, 'Pathum Thani']),
            $this->addPupilKeys([3564, 217, 'Pattani']),
            $this->addPupilKeys([3565, 217, 'Phangnga']),
            $this->addPupilKeys([3566, 217, 'Phatthalung']),
            $this->addPupilKeys([3567, 217, 'Phayao']),
            $this->addPupilKeys([3568, 217, 'Phetchabun']),
            $this->addPupilKeys([3569, 217, 'Phetchaburi']),
            $this->addPupilKeys([3570, 217, 'Phichit']),
            $this->addPupilKeys([3571, 217, 'Phitsanulok']),
            $this->addPupilKeys([3572, 217, 'Phra Nakhon Si Ayutthaya']),
            $this->addPupilKeys([3573, 217, 'Phrae']),
            $this->addPupilKeys([3574, 217, 'Phuket']),
            $this->addPupilKeys([3575, 217, 'Prachin Buri']),
            $this->addPupilKeys([3576, 217, 'Prachuap Khiri Khan']),
            $this->addPupilKeys([3577, 217, 'Ranong']),
            $this->addPupilKeys([3578, 217, 'Ratchaburi']),
            $this->addPupilKeys([3579, 217, 'Rayong']),
            $this->addPupilKeys([3580, 217, 'Roi Et']),
            $this->addPupilKeys([3581, 217, 'Sa Kaeo']),
            $this->addPupilKeys([3582, 217, 'Sakon Nakhon']),
            $this->addPupilKeys([3583, 217, 'Samut Prakan']),
            $this->addPupilKeys([3584, 217, 'Samut Sakhon']),
            $this->addPupilKeys([3585, 217, 'Samut Songkhran']),
            $this->addPupilKeys([3586, 217, 'Saraburi']),
            $this->addPupilKeys([3587, 217, 'Satun']),
            $this->addPupilKeys([3588, 217, 'Si Sa Ket']),
            $this->addPupilKeys([3589, 217, 'Sing Buri']),
            $this->addPupilKeys([3590, 217, 'Songkhla']),
            $this->addPupilKeys([3591, 217, 'Sukhothai']),
            $this->addPupilKeys([3592, 217, 'Suphan Buri']),
            $this->addPupilKeys([3593, 217, 'Surat Thani']),
            $this->addPupilKeys([3594, 217, 'Surin']),
            $this->addPupilKeys([3595, 217, 'Tak']),
            $this->addPupilKeys([3596, 217, 'Trang']),
            $this->addPupilKeys([3597, 217, 'Trat']),
            $this->addPupilKeys([3598, 217, 'Ubon Ratchathani']),
            $this->addPupilKeys([3599, 217, 'Udon Thani']),
            $this->addPupilKeys([3600, 217, 'Uthai Thani']),
            $this->addPupilKeys([3601, 217, 'Uttaradit']),
            $this->addPupilKeys([3602, 217, 'Yala']),
            $this->addPupilKeys([3603, 217, 'Yasothon']),
            $this->addPupilKeys([3604, 218, 'Centre']),
            $this->addPupilKeys([3605, 218, 'Kara']),
            $this->addPupilKeys([3606, 218, 'Maritime']),
            $this->addPupilKeys([3607, 218, 'Plateaux']),
            $this->addPupilKeys([3608, 218, 'Savanes']),
            $this->addPupilKeys([3609, 219, 'Atafu']),
            $this->addPupilKeys([3610, 219, 'Fakaofo']),
            $this->addPupilKeys([3611, 219, 'Nukunonu']),
            $this->addPupilKeys([3612, 220, 'Eua']),
            $this->addPupilKeys([3613, 220, 'Ha\'apai']),
            $this->addPupilKeys([3614, 220, 'Niuas']),
            $this->addPupilKeys([3615, 220, 'Tongatapu']),
            $this->addPupilKeys([3616, 220, 'Vava\'u']),
            $this->addPupilKeys([3617, 221, 'Arima-Tunapuna-Piarco']),
            $this->addPupilKeys([3618, 221, 'Caroni']),
            $this->addPupilKeys([3619, 221, 'Chaguanas']),
            $this->addPupilKeys([3620, 221, 'Couva-Tabaquite-Talparo']),
            $this->addPupilKeys([3621, 221, 'Diego Martin']),
            $this->addPupilKeys([3622, 221, 'Glencoe']),
            $this->addPupilKeys([3623, 221, 'Penal Debe']),
            $this->addPupilKeys([3624, 221, 'Point Fortin']),
            $this->addPupilKeys([3625, 221, 'Port of Spain']),
            $this->addPupilKeys([3626, 221, 'Princes Town']),
            $this->addPupilKeys([3627, 221, 'Saint George']),
            $this->addPupilKeys([3628, 221, 'San Fernando']),
            $this->addPupilKeys([3629, 221, 'San Juan']),
            $this->addPupilKeys([3630, 221, 'Sangre Grande']),
            $this->addPupilKeys([3631, 221, 'Siparia']),
            $this->addPupilKeys([3632, 221, 'Tobago']),
            $this->addPupilKeys([3633, 222, 'Aryanah']),
            $this->addPupilKeys([3634, 222, 'Bajah']),
            $this->addPupilKeys([3635, 222, 'Bin \'Arus']),
            $this->addPupilKeys([3636, 222, 'Binzart']),
            $this->addPupilKeys([3637, 222, 'Gouvernorat de Ariana']),
            $this->addPupilKeys([3638, 222, 'Gouvernorat de Nabeul']),
            $this->addPupilKeys([3639, 222, 'Gouvernorat de Sousse']),
            $this->addPupilKeys([3640, 222, 'Hammamet Yasmine']),
            $this->addPupilKeys([3641, 222, 'Jundubah']),
            $this->addPupilKeys([3642, 222, 'Madaniyin']),
            $this->addPupilKeys([3643, 222, 'Manubah']),
            $this->addPupilKeys([3644, 222, 'Monastir']),
            $this->addPupilKeys([3645, 222, 'Nabul']),
            $this->addPupilKeys([3646, 222, 'Qabis']),
            $this->addPupilKeys([3647, 222, 'Qafsah']),
            $this->addPupilKeys([3648, 222, 'Qibili']),
            $this->addPupilKeys([3649, 222, 'Safaqis']),
            $this->addPupilKeys([3650, 222, 'Sfax']),
            $this->addPupilKeys([3651, 222, 'Sidi Bu Zayd']),
            $this->addPupilKeys([3652, 222, 'Silyanah']),
            $this->addPupilKeys([3653, 222, 'Susah']),
            $this->addPupilKeys([3654, 222, 'Tatawin']),
            $this->addPupilKeys([3655, 222, 'Tawzar']),
            $this->addPupilKeys([3656, 222, 'Tunis']),
            $this->addPupilKeys([3657, 222, 'Zaghwan']),
            $this->addPupilKeys([3658, 222, 'al-Kaf']),
            $this->addPupilKeys([3659, 222, 'al-Mahdiyah']),
            $this->addPupilKeys([3660, 222, 'al-Munastir']),
            $this->addPupilKeys([3661, 222, 'al-Qasrayn']),
            $this->addPupilKeys([3662, 222, 'al-Qayrawan']),
            $this->addPupilKeys([3663, 223, 'Adana']),
            $this->addPupilKeys([3664, 223, 'Adiyaman']),
            $this->addPupilKeys([3665, 223, 'Afyon']),
            $this->addPupilKeys([3666, 223, 'Agri']),
            $this->addPupilKeys([3667, 223, 'Aksaray']),
            $this->addPupilKeys([3668, 223, 'Amasya']),
            $this->addPupilKeys([3669, 223, 'Ankara']),
            $this->addPupilKeys([3670, 223, 'Antalya']),
            $this->addPupilKeys([3671, 223, 'Ardahan']),
            $this->addPupilKeys([3672, 223, 'Artvin']),
            $this->addPupilKeys([3673, 223, 'Aydin']),
            $this->addPupilKeys([3674, 223, 'Balikesir']),
            $this->addPupilKeys([3675, 223, 'Bartin']),
            $this->addPupilKeys([3676, 223, 'Batman']),
            $this->addPupilKeys([3677, 223, 'Bayburt']),
            $this->addPupilKeys([3678, 223, 'Bilecik']),
            $this->addPupilKeys([3679, 223, 'Bingol']),
            $this->addPupilKeys([3680, 223, 'Bitlis']),
            $this->addPupilKeys([3681, 223, 'Bolu']),
            $this->addPupilKeys([3682, 223, 'Burdur']),
            $this->addPupilKeys([3683, 223, 'Bursa']),
            $this->addPupilKeys([3684, 223, 'Canakkale']),
            $this->addPupilKeys([3685, 223, 'Cankiri']),
            $this->addPupilKeys([3686, 223, 'Corum']),
            $this->addPupilKeys([3687, 223, 'Denizli']),
            $this->addPupilKeys([3688, 223, 'Diyarbakir']),
            $this->addPupilKeys([3689, 223, 'Duzce']),
            $this->addPupilKeys([3690, 223, 'Edirne']),
            $this->addPupilKeys([3691, 223, 'Elazig']),
            $this->addPupilKeys([3692, 223, 'Erzincan']),
            $this->addPupilKeys([3693, 223, 'Erzurum']),
            $this->addPupilKeys([3694, 223, 'Eskisehir']),
            $this->addPupilKeys([3695, 223, 'Gaziantep']),
            $this->addPupilKeys([3696, 223, 'Giresun']),
            $this->addPupilKeys([3697, 223, 'Gumushane']),
            $this->addPupilKeys([3698, 223, 'Hakkari']),
            $this->addPupilKeys([3699, 223, 'Hatay']),
            $this->addPupilKeys([3700, 223, 'Icel']),
            $this->addPupilKeys([3701, 223, 'Igdir']),
            $this->addPupilKeys([3702, 223, 'Isparta']),
            $this->addPupilKeys([3703, 223, 'Istanbul']),
            $this->addPupilKeys([3704, 223, 'Izmir']),
            $this->addPupilKeys([3705, 223, 'Kahramanmaras']),
            $this->addPupilKeys([3706, 223, 'Karabuk']),
            $this->addPupilKeys([3707, 223, 'Karaman']),
            $this->addPupilKeys([3708, 223, 'Kars']),
            $this->addPupilKeys([3709, 223, 'Karsiyaka']),
            $this->addPupilKeys([3710, 223, 'Kastamonu']),
            $this->addPupilKeys([3711, 223, 'Kayseri']),
            $this->addPupilKeys([3712, 223, 'Kilis']),
            $this->addPupilKeys([3713, 223, 'Kirikkale']),
            $this->addPupilKeys([3714, 223, 'Kirklareli']),
            $this->addPupilKeys([3715, 223, 'Kirsehir']),
            $this->addPupilKeys([3716, 223, 'Kocaeli']),
            $this->addPupilKeys([3717, 223, 'Konya']),
            $this->addPupilKeys([3718, 223, 'Kutahya']),
            $this->addPupilKeys([3719, 223, 'Lefkosa']),
            $this->addPupilKeys([3720, 223, 'Malatya']),
            $this->addPupilKeys([3721, 223, 'Manisa']),
            $this->addPupilKeys([3722, 223, 'Mardin']),
            $this->addPupilKeys([3723, 223, 'Mugla']),
            $this->addPupilKeys([3724, 223, 'Mus']),
            $this->addPupilKeys([3725, 223, 'Nevsehir']),
            $this->addPupilKeys([3726, 223, 'Nigde']),
            $this->addPupilKeys([3727, 223, 'Ordu']),
            $this->addPupilKeys([3728, 223, 'Osmaniye']),
            $this->addPupilKeys([3729, 223, 'Rize']),
            $this->addPupilKeys([3730, 223, 'Sakarya']),
            $this->addPupilKeys([3731, 223, 'Samsun']),
            $this->addPupilKeys([3732, 223, 'Sanliurfa']),
            $this->addPupilKeys([3733, 223, 'Siirt']),
            $this->addPupilKeys([3734, 223, 'Sinop']),
            $this->addPupilKeys([3735, 223, 'Sirnak']),
            $this->addPupilKeys([3736, 223, 'Sivas']),
            $this->addPupilKeys([3737, 223, 'Tekirdag']),
            $this->addPupilKeys([3738, 223, 'Tokat']),
            $this->addPupilKeys([3739, 223, 'Trabzon']),
            $this->addPupilKeys([3740, 223, 'Tunceli']),
            $this->addPupilKeys([3741, 223, 'Usak']),
            $this->addPupilKeys([3742, 223, 'Van']),
            $this->addPupilKeys([3743, 223, 'Yalova']),
            $this->addPupilKeys([3744, 223, 'Yozgat']),
            $this->addPupilKeys([3745, 223, 'Zonguldak']),
            $this->addPupilKeys([3746, 224, 'Ahal']),
            $this->addPupilKeys([3747, 224, 'Asgabat']),
            $this->addPupilKeys([3748, 224, 'Balkan']),
            $this->addPupilKeys([3749, 224, 'Dasoguz']),
            $this->addPupilKeys([3750, 224, 'Lebap']),
            $this->addPupilKeys([3751, 224, 'Mari']),
            $this->addPupilKeys([3752, 225, 'Grand Turk']),
            $this->addPupilKeys([3753, 225, 'South Caicos and East Caicos']),
            $this->addPupilKeys([3754, 226, 'Funafuti']),
            $this->addPupilKeys([3755, 226, 'Nanumanga']),
            $this->addPupilKeys([3756, 226, 'Nanumea']),
            $this->addPupilKeys([3757, 226, 'Niutao']),
            $this->addPupilKeys([3758, 226, 'Nui']),
            $this->addPupilKeys([3759, 226, 'Nukufetau']),
            $this->addPupilKeys([3760, 226, 'Nukulaelae']),
            $this->addPupilKeys([3761, 226, 'Vaitupu']),
            $this->addPupilKeys([3762, 227, 'Central']),
            $this->addPupilKeys([3763, 227, 'Eastern']),
            $this->addPupilKeys([3764, 227, 'Northern']),
            $this->addPupilKeys([3765, 227, 'Western']),
            $this->addPupilKeys([3766, 228, 'Cherkas\'ka']),
            $this->addPupilKeys([3767, 228, 'Chernihivs\'ka']),
            $this->addPupilKeys([3768, 228, 'Chernivets\'ka']),
            $this->addPupilKeys([3769, 228, 'Crimea']),
            $this->addPupilKeys([3770, 228, 'Dnipropetrovska']),
            $this->addPupilKeys([3771, 228, 'Donets\'ka']),
            $this->addPupilKeys([3772, 228, 'Ivano-Frankivs\'ka']),
            $this->addPupilKeys([3773, 228, 'Kharkiv']),
            $this->addPupilKeys([3774, 228, 'Kharkov']),
            $this->addPupilKeys([3775, 228, 'Khersonska']),
            $this->addPupilKeys([3776, 228, 'Khmel\'nyts\'ka']),
            $this->addPupilKeys([3777, 228, 'Kirovohrad']),
            $this->addPupilKeys([3778, 228, 'Krym']),
            $this->addPupilKeys([3779, 228, 'Kyyiv']),
            $this->addPupilKeys([3780, 228, 'Kyyivs\'ka']),
            $this->addPupilKeys([3781, 228, 'L\'vivs\'ka']),
            $this->addPupilKeys([3782, 228, 'Luhans\'ka']),
            $this->addPupilKeys([3783, 228, 'Mykolayivs\'ka']),
            $this->addPupilKeys([3784, 228, 'Odes\'ka']),
            $this->addPupilKeys([3785, 228, 'Odessa']),
            $this->addPupilKeys([3786, 228, 'Poltavs\'ka']),
            $this->addPupilKeys([3787, 228, 'Rivnens\'ka']),
            $this->addPupilKeys([3788, 228, 'Sevastopol\'']),
            $this->addPupilKeys([3789, 228, 'Sums\'ka']),
            $this->addPupilKeys([3790, 228, 'Ternopil\'s\'ka']),
            $this->addPupilKeys([3791, 228, 'Volyns\'ka']),
            $this->addPupilKeys([3792, 228, 'Vynnyts\'ka']),
            $this->addPupilKeys([3793, 228, 'Zakarpats\'ka']),
            $this->addPupilKeys([3794, 228, 'Zaporizhia']),
            $this->addPupilKeys([3795, 228, 'Zhytomyrs\'ka']),
            $this->addPupilKeys([3796, 229, 'Abu Zabi']),
            $this->addPupilKeys([3797, 229, 'Ajman']),
            $this->addPupilKeys([3798, 229, 'Dubai']),
            $this->addPupilKeys([3799, 229, 'Ras al-Khaymah']),
            $this->addPupilKeys([3800, 229, 'Sharjah']),
            $this->addPupilKeys([3801, 229, 'Sharjha']),
            $this->addPupilKeys([3802, 229, 'Umm al Qaywayn']),
            $this->addPupilKeys([3803, 229, 'al-Fujayrah']),
            $this->addPupilKeys([3804, 229, 'ash-Shariqah']),
            $this->addPupilKeys([3805, 230, 'Aberdeen']),
            $this->addPupilKeys([3806, 230, 'Aberdeenshire']),
            $this->addPupilKeys([3807, 230, 'Argyll']),
            $this->addPupilKeys([3808, 230, 'Armagh']),
            $this->addPupilKeys([3809, 230, 'Bedfordshire']),
            $this->addPupilKeys([3810, 230, 'Belfast']),
            $this->addPupilKeys([3811, 230, 'Berkshire']),
            $this->addPupilKeys([3812, 230, 'Birmingham']),
            $this->addPupilKeys([3813, 230, 'Brechin']),
            $this->addPupilKeys([3814, 230, 'Bridgnorth']),
            $this->addPupilKeys([3815, 230, 'Bristol']),
            $this->addPupilKeys([3816, 230, 'Buckinghamshire']),
            $this->addPupilKeys([3817, 230, 'Cambridge']),
            $this->addPupilKeys([3818, 230, 'Cambridgeshire']),
            $this->addPupilKeys([3819, 230, 'Channel Islands']),
            $this->addPupilKeys([3820, 230, 'Cheshire']),
            $this->addPupilKeys([3821, 230, 'Cleveland']),
            $this->addPupilKeys([3822, 230, 'Co Fermanagh']),
            $this->addPupilKeys([3823, 230, 'Conwy']),
            $this->addPupilKeys([3824, 230, 'Cornwall']),
            $this->addPupilKeys([3825, 230, 'Coventry']),
            $this->addPupilKeys([3826, 230, 'Craven Arms']),
            $this->addPupilKeys([3827, 230, 'Cumbria']),
            $this->addPupilKeys([3828, 230, 'Denbighshire']),
            $this->addPupilKeys([3829, 230, 'Derby']),
            $this->addPupilKeys([3830, 230, 'Derbyshire']),
            $this->addPupilKeys([3831, 230, 'Devon']),
            $this->addPupilKeys([3832, 230, 'Dial Code Dungannon']),
            $this->addPupilKeys([3833, 230, 'Didcot']),
            $this->addPupilKeys([3834, 230, 'Dorset']),
            $this->addPupilKeys([3835, 230, 'Dunbartonshire']),
            $this->addPupilKeys([3836, 230, 'Durham']),
            $this->addPupilKeys([3837, 230, 'East Dunbartonshire']),
            $this->addPupilKeys([3838, 230, 'East Lothian']),
            $this->addPupilKeys([3839, 230, 'East Midlands']),
            $this->addPupilKeys([3840, 230, 'East Sussex']),
            $this->addPupilKeys([3841, 230, 'East Yorkshire']),
            $this->addPupilKeys([3842, 230, 'England']),
            $this->addPupilKeys([3843, 230, 'Essex']),
            $this->addPupilKeys([3844, 230, 'Fermanagh']),
            $this->addPupilKeys([3845, 230, 'Fife']),
            $this->addPupilKeys([3846, 230, 'Flintshire']),
            $this->addPupilKeys([3847, 230, 'Fulham']),
            $this->addPupilKeys([3848, 230, 'Gainsborough']),
            $this->addPupilKeys([3849, 230, 'Glocestershire']),
            $this->addPupilKeys([3850, 230, 'Gwent']),
            $this->addPupilKeys([3851, 230, 'Hampshire']),
            $this->addPupilKeys([3852, 230, 'Hants']),
            $this->addPupilKeys([3853, 230, 'Herefordshire']),
            $this->addPupilKeys([3854, 230, 'Hertfordshire']),
            $this->addPupilKeys([3855, 230, 'Ireland']),
            $this->addPupilKeys([3856, 230, 'Isle Of Man']),
            $this->addPupilKeys([3857, 230, 'Isle of Wight']),
            $this->addPupilKeys([3858, 230, 'Kenford']),
            $this->addPupilKeys([3859, 230, 'Kent']),
            $this->addPupilKeys([3860, 230, 'Kilmarnock']),
            $this->addPupilKeys([3861, 230, 'Lanarkshire']),
            $this->addPupilKeys([3862, 230, 'Lancashire']),
            $this->addPupilKeys([3863, 230, 'Leicestershire']),
            $this->addPupilKeys([3864, 230, 'Lincolnshire']),
            $this->addPupilKeys([3865, 230, 'Llanymynech']),
            $this->addPupilKeys([3866, 230, 'London']),
            $this->addPupilKeys([3867, 230, 'Ludlow']),
            $this->addPupilKeys([3868, 230, 'Manchester']),
            $this->addPupilKeys([3869, 230, 'Mayfair']),
            $this->addPupilKeys([3870, 230, 'Merseyside']),
            $this->addPupilKeys([3871, 230, 'Mid Glamorgan']),
            $this->addPupilKeys([3872, 230, 'Middlesex']),
            $this->addPupilKeys([3873, 230, 'Mildenhall']),
            $this->addPupilKeys([3874, 230, 'Monmouthshire']),
            $this->addPupilKeys([3875, 230, 'Newton Stewart']),
            $this->addPupilKeys([3876, 230, 'Norfolk']),
            $this->addPupilKeys([3877, 230, 'North Humberside']),
            $this->addPupilKeys([3878, 230, 'North Yorkshire']),
            $this->addPupilKeys([3879, 230, 'Northamptonshire']),
            $this->addPupilKeys([3880, 230, 'Northants']),
            $this->addPupilKeys([3881, 230, 'Northern Ireland']),
            $this->addPupilKeys([3882, 230, 'Northumberland']),
            $this->addPupilKeys([3883, 230, 'Nottinghamshire']),
            $this->addPupilKeys([3884, 230, 'Oxford']),
            $this->addPupilKeys([3885, 230, 'Powys']),
            $this->addPupilKeys([3886, 230, 'Roos-shire']),
            $this->addPupilKeys([3887, 230, 'SUSSEX']),
            $this->addPupilKeys([3888, 230, 'Sark']),
            $this->addPupilKeys([3889, 230, 'Scotland']),
            $this->addPupilKeys([3890, 230, 'Scottish Borders']),
            $this->addPupilKeys([3891, 230, 'Shropshire']),
            $this->addPupilKeys([3892, 230, 'Somerset']),
            $this->addPupilKeys([3893, 230, 'South Glamorgan']),
            $this->addPupilKeys([3894, 230, 'South Wales']),
            $this->addPupilKeys([3895, 230, 'South Yorkshire']),
            $this->addPupilKeys([3896, 230, 'Southwell']),
            $this->addPupilKeys([3897, 230, 'Staffordshire']),
            $this->addPupilKeys([3898, 230, 'Strabane']),
            $this->addPupilKeys([3899, 230, 'Suffolk']),
            $this->addPupilKeys([3900, 230, 'Surrey']),
            $this->addPupilKeys([3901, 230, 'Sussex']),
            $this->addPupilKeys([3902, 230, 'Twickenham']),
            $this->addPupilKeys([3903, 230, 'Tyne and Wear']),
            $this->addPupilKeys([3904, 230, 'Tyrone']),
            $this->addPupilKeys([3905, 230, 'Utah']),
            $this->addPupilKeys([3906, 230, 'Wales']),
            $this->addPupilKeys([3907, 230, 'Warwickshire']),
            $this->addPupilKeys([3908, 230, 'West Lothian']),
            $this->addPupilKeys([3909, 230, 'West Midlands']),
            $this->addPupilKeys([3910, 230, 'West Sussex']),
            $this->addPupilKeys([3911, 230, 'West Yorkshire']),
            $this->addPupilKeys([3912, 230, 'Whissendine']),
            $this->addPupilKeys([3913, 230, 'Wiltshire']),
            $this->addPupilKeys([3914, 230, 'Wokingham']),
            $this->addPupilKeys([3915, 230, 'Worcestershire']),
            $this->addPupilKeys([3916, 230, 'Wrexham']),
            $this->addPupilKeys([3917, 230, 'Wurttemberg']),
            $this->addPupilKeys([3918, 230, 'Yorkshire']),
            $this->addPupilKeys([3919, 231, 'Alabama']),
            $this->addPupilKeys([3920, 231, 'Alaska']),
            $this->addPupilKeys([3921, 231, 'Arizona']),
            $this->addPupilKeys([3922, 231, 'Arkansas']),
            $this->addPupilKeys([3923, 231, 'Byram']),
            $this->addPupilKeys([3924, 231, 'California']),
            $this->addPupilKeys([3925, 231, 'Cokato']),
            $this->addPupilKeys([3926, 231, 'Colorado']),
            $this->addPupilKeys([3927, 231, 'Connecticut']),
            $this->addPupilKeys([3928, 231, 'Delaware']),
            $this->addPupilKeys([3929, 231, 'District of Columbia']),
            $this->addPupilKeys([3930, 231, 'Florida']),
            $this->addPupilKeys([3931, 231, 'Georgia']),
            $this->addPupilKeys([3932, 231, 'Hawaii']),
            $this->addPupilKeys([3933, 231, 'Idaho']),
            $this->addPupilKeys([3934, 231, 'Illinois']),
            $this->addPupilKeys([3935, 231, 'Indiana']),
            $this->addPupilKeys([3936, 231, 'Iowa']),
            $this->addPupilKeys([3937, 231, 'Kansas']),
            $this->addPupilKeys([3938, 231, 'Kentucky']),
            $this->addPupilKeys([3939, 231, 'Louisiana']),
            $this->addPupilKeys([3940, 231, 'Lowa']),
            $this->addPupilKeys([3941, 231, 'Maine']),
            $this->addPupilKeys([3942, 231, 'Maryland']),
            $this->addPupilKeys([3943, 231, 'Massachusetts']),
            $this->addPupilKeys([3944, 231, 'Medfield']),
            $this->addPupilKeys([3945, 231, 'Michigan']),
            $this->addPupilKeys([3946, 231, 'Minnesota']),
            $this->addPupilKeys([3947, 231, 'Mississippi']),
            $this->addPupilKeys([3948, 231, 'Missouri']),
            $this->addPupilKeys([3949, 231, 'Montana']),
            $this->addPupilKeys([3950, 231, 'Nebraska']),
            $this->addPupilKeys([3951, 231, 'Nevada']),
            $this->addPupilKeys([3952, 231, 'New Hampshire']),
            $this->addPupilKeys([3953, 231, 'New Jersey']),
            $this->addPupilKeys([3954, 231, 'New Jersy']),
            $this->addPupilKeys([3955, 231, 'New Mexico']),
            $this->addPupilKeys([3956, 231, 'New York']),
            $this->addPupilKeys([3957, 231, 'North Carolina']),
            $this->addPupilKeys([3958, 231, 'North Dakota']),
            $this->addPupilKeys([3959, 231, 'Ohio']),
            $this->addPupilKeys([3960, 231, 'Oklahoma']),
            $this->addPupilKeys([3961, 231, 'Ontario']),
            $this->addPupilKeys([3962, 231, 'Oregon']),
            $this->addPupilKeys([3963, 231, 'Pennsylvania']),
            $this->addPupilKeys([3964, 231, 'Ramey']),
            $this->addPupilKeys([3965, 231, 'Rhode Island']),
            $this->addPupilKeys([3966, 231, 'South Carolina']),
            $this->addPupilKeys([3967, 231, 'South Dakota']),
            $this->addPupilKeys([3968, 231, 'Sublimity']),
            $this->addPupilKeys([3969, 231, 'Tennessee']),
            $this->addPupilKeys([3970, 231, 'Texas']),
            $this->addPupilKeys([3971, 231, 'Trimble']),
            $this->addPupilKeys([3972, 231, 'Utah']),
            $this->addPupilKeys([3973, 231, 'Vermont']),
            $this->addPupilKeys([3974, 231, 'Virginia']),
            $this->addPupilKeys([3975, 231, 'Washington']),
            $this->addPupilKeys([3976, 231, 'West Virginia']),
            $this->addPupilKeys([3977, 231, 'Wisconsin']),
            $this->addPupilKeys([3978, 231, 'Wyoming']),
            $this->addPupilKeys([3979, 232, 'United States Minor Outlying I']),
            $this->addPupilKeys([3980, 233, 'Artigas']),
            $this->addPupilKeys([3981, 233, 'Canelones']),
            $this->addPupilKeys([3982, 233, 'Cerro Largo']),
            $this->addPupilKeys([3983, 233, 'Colonia']),
            $this->addPupilKeys([3984, 233, 'Durazno']),
            $this->addPupilKeys([3985, 233, 'FLorida']),
            $this->addPupilKeys([3986, 233, 'Flores']),
            $this->addPupilKeys([3987, 233, 'Lavalleja']),
            $this->addPupilKeys([3988, 233, 'Maldonado']),
            $this->addPupilKeys([3989, 233, 'Montevideo']),
            $this->addPupilKeys([3990, 233, 'Paysandu']),
            $this->addPupilKeys([3991, 233, 'Rio Negro']),
            $this->addPupilKeys([3992, 233, 'Rivera']),
            $this->addPupilKeys([3993, 233, 'Rocha']),
            $this->addPupilKeys([3994, 233, 'Salto']),
            $this->addPupilKeys([3995, 233, 'San Jose']),
            $this->addPupilKeys([3996, 233, 'Soriano']),
            $this->addPupilKeys([3997, 233, 'Tacuarembo']),
            $this->addPupilKeys([3998, 233, 'Treinta y Tres']),
            $this->addPupilKeys([3999, 234, 'Andijon']),
            $this->addPupilKeys([4000, 234, 'Buhoro']),
            $this->addPupilKeys([4001, 234, 'Buxoro Viloyati']),
            $this->addPupilKeys([4002, 234, 'Cizah']),
            $this->addPupilKeys([4003, 234, 'Fargona']),
            $this->addPupilKeys([4004, 234, 'Horazm']),
            $this->addPupilKeys([4005, 234, 'Kaskadar']),
            $this->addPupilKeys([4006, 234, 'Korakalpogiston']),
            $this->addPupilKeys([4007, 234, 'Namangan']),
            $this->addPupilKeys([4008, 234, 'Navoi']),
            $this->addPupilKeys([4009, 234, 'Samarkand']),
            $this->addPupilKeys([4010, 234, 'Sirdare']),
            $this->addPupilKeys([4011, 234, 'Surhondar']),
            $this->addPupilKeys([4012, 234, 'Toskent']),
            $this->addPupilKeys([4013, 235, 'Malampa']),
            $this->addPupilKeys([4014, 235, 'Penama']),
            $this->addPupilKeys([4015, 235, 'Sanma']),
            $this->addPupilKeys([4016, 235, 'Shefa']),
            $this->addPupilKeys([4017, 235, 'Tafea']),
            $this->addPupilKeys([4018, 235, 'Torba']),
            $this->addPupilKeys([4019, 236, 'Holy See (Vatican City)']),
            $this->addPupilKeys([4020, 237, 'Amazonas']),
            $this->addPupilKeys([4021, 237, 'Anzoategui']),
            $this->addPupilKeys([4022, 237, 'Apure']),
            $this->addPupilKeys([4023, 237, 'Aragua']),
            $this->addPupilKeys([4024, 237, 'Barinas']),
            $this->addPupilKeys([4025, 237, 'Bolivar']),
            $this->addPupilKeys([4026, 237, 'Carabobo']),
            $this->addPupilKeys([4027, 237, 'Cojedes']),
            $this->addPupilKeys([4028, 237, 'Delta Amacuro']),
            $this->addPupilKeys([4029, 237, 'Distrito Federal']),
            $this->addPupilKeys([4030, 237, 'Falcon']),
            $this->addPupilKeys([4031, 237, 'Guarico']),
            $this->addPupilKeys([4032, 237, 'Lara']),
            $this->addPupilKeys([4033, 237, 'Merida']),
            $this->addPupilKeys([4034, 237, 'Miranda']),
            $this->addPupilKeys([4035, 237, 'Monagas']),
            $this->addPupilKeys([4036, 237, 'Nueva Esparta']),
            $this->addPupilKeys([4037, 237, 'Portuguesa']),
            $this->addPupilKeys([4038, 237, 'Sucre']),
            $this->addPupilKeys([4039, 237, 'Tachira']),
            $this->addPupilKeys([4040, 237, 'Trujillo']),
            $this->addPupilKeys([4041, 237, 'Vargas']),
            $this->addPupilKeys([4042, 237, 'Yaracuy']),
            $this->addPupilKeys([4043, 237, 'Zulia']),
            $this->addPupilKeys([4044, 238, 'Bac Giang']),
            $this->addPupilKeys([4045, 238, 'Binh Dinh']),
            $this->addPupilKeys([4046, 238, 'Binh Duong']),
            $this->addPupilKeys([4047, 238, 'Da Nang']),
            $this->addPupilKeys([4048, 238, 'Dong Bang Song Cuu Long']),
            $this->addPupilKeys([4049, 238, 'Dong Bang Song Hong']),
            $this->addPupilKeys([4050, 238, 'Dong Nai']),
            $this->addPupilKeys([4051, 238, 'Dong Nam Bo']),
            $this->addPupilKeys([4052, 238, 'Duyen Hai Mien Trung']),
            $this->addPupilKeys([4053, 238, 'Hanoi']),
            $this->addPupilKeys([4054, 238, 'Hung Yen']),
            $this->addPupilKeys([4055, 238, 'Khu Bon Cu']),
            $this->addPupilKeys([4056, 238, 'Long An']),
            $this->addPupilKeys([4057, 238, 'Mien Nui Va Trung Du']),
            $this->addPupilKeys([4058, 238, 'Thai Nguyen']),
            $this->addPupilKeys([4059, 238, 'Thanh Pho Ho Chi Minh']),
            $this->addPupilKeys([4060, 238, 'Thu Do Ha Noi']),
            $this->addPupilKeys([4061, 238, 'Tinh Can Tho']),
            $this->addPupilKeys([4062, 238, 'Tinh Da Nang']),
            $this->addPupilKeys([4063, 238, 'Tinh Gia Lai']),
            $this->addPupilKeys([4064, 239, 'Anegada']),
            $this->addPupilKeys([4065, 239, 'Jost van Dyke']),
            $this->addPupilKeys([4066, 239, 'Tortola']),
            $this->addPupilKeys([4067, 240, 'Saint Croix']),
            $this->addPupilKeys([4068, 240, 'Saint John']),
            $this->addPupilKeys([4069, 240, 'Saint Thomas']),
            $this->addPupilKeys([4070, 241, 'Alo']),
            $this->addPupilKeys([4071, 241, 'Singave']),
            $this->addPupilKeys([4072, 241, 'Wallis']),
            $this->addPupilKeys([4073, 242, 'Bu Jaydur']),
            $this->addPupilKeys([4074, 242, 'Wad-adh-Dhahab']),
            $this->addPupilKeys([4075, 242, 'al-\'Ayun']),
            $this->addPupilKeys([4076, 242, 'as-Samarah']),
            $this->addPupilKeys([4077, 243, '\'Adan']),
            $this->addPupilKeys([4078, 243, 'Abyan']),
            $this->addPupilKeys([4079, 243, 'Dhamar']),
            $this->addPupilKeys([4080, 243, 'Hadramaut']),
            $this->addPupilKeys([4081, 243, 'Hajjah']),
            $this->addPupilKeys([4082, 243, 'Hudaydah']),
            $this->addPupilKeys([4083, 243, 'Ibb']),
            $this->addPupilKeys([4084, 243, 'Lahij']),
            $this->addPupilKeys([4085, 243, 'Ma\'rib']),
            $this->addPupilKeys([4086, 243, 'Madinat San\'a']),
            $this->addPupilKeys([4087, 243, 'Sa\'dah']),
            $this->addPupilKeys([4088, 243, 'Sana']),
            $this->addPupilKeys([4089, 243, 'Shabwah']),
            $this->addPupilKeys([4090, 243, 'Ta\'izz']),
            $this->addPupilKeys([4091, 243, 'al-Bayda']),
            $this->addPupilKeys([4092, 243, 'al-Hudaydah']),
            $this->addPupilKeys([4093, 243, 'al-Jawf']),
            $this->addPupilKeys([4094, 243, 'al-Mahrah']),
            $this->addPupilKeys([4095, 243, 'al-Mahwit']),
            $this->addPupilKeys([4096, 244, 'Central Serbia']),
            $this->addPupilKeys([4097, 244, 'Kosovo and Metohija']),
            $this->addPupilKeys([4098, 244, 'Montenegro']),
            $this->addPupilKeys([4099, 244, 'Republic of Serbia']),
            $this->addPupilKeys([4100, 244, 'Serbia']),
            $this->addPupilKeys([4101, 244, 'Vojvodina']),
            $this->addPupilKeys([4102, 245, 'Central']),
            $this->addPupilKeys([4103, 245, 'Copperbelt']),
            $this->addPupilKeys([4104, 245, 'Eastern']),
            $this->addPupilKeys([4105, 245, 'Luapala']),
            $this->addPupilKeys([4106, 245, 'Lusaka']),
            $this->addPupilKeys([4107, 245, 'North-Western']),
            $this->addPupilKeys([4108, 245, 'Northern']),
            $this->addPupilKeys([4109, 245, 'Southern']),
            $this->addPupilKeys([4110, 245, 'Western']),
            $this->addPupilKeys([4111, 246, 'Bulawayo']),
            $this->addPupilKeys([4112, 246, 'Harare']),
            $this->addPupilKeys([4113, 246, 'Manicaland']),
            $this->addPupilKeys([4114, 246, 'Mashonaland Central']),
            $this->addPupilKeys([4115, 246, 'Mashonaland East']),
            $this->addPupilKeys([4116, 246, 'Mashonaland West']),
            $this->addPupilKeys([4117, 246, 'Masvingo']),
            $this->addPupilKeys([4118, 246, 'Matabeleland North']),
            $this->addPupilKeys([4119, 246, 'Matabeleland South']),
            $this->addPupilKeys([4120, 246, 'Midlands']),
        ];

        DB::table('states')->insert($states);
    }
}

class UserGateSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('approved', true)->get();
        foreach ($users as $user) {
            $found = UserGate::where('users_id', $user->id)->where('saver_users_id', $user->id)->where('key', 'enable-all')->first();
            if (!$found) {
                UserGate::insert([
                    'key' => 'enable-all',
                    'users_id' => $user->id,
                    'saver_users_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }

        }
    }
}

class OhsRiskownerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $sw = 0;
        $ohs = Ohs::all();
        $data = [];
        foreach ($ohs as $o) {
            if ($o->dept) {
                $user_id = User::where('email', $o->dept->hod)->value('id') ?? 0;
                $data[] = [
                    'user_id' => $user_id,
                    'ohs_id' => $o->id,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
                $found = DB::table('ohs_riskowners')->where('ohs_id', $o->id)->where('user_id', $user_id)->first();
                if (!$found) {
                    $sw = 1;
                }
            }
        }
        if ($sw) {
            DB::table('ohs_riskowners')->insert($data);
        }

    }
}
class StandardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'ISO 9001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 14001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 45001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'API Q1',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 27001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 22000',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('standards')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('standards')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [[
            'name' => 'product',
            'module' => 'customer_satisfaction',
            'created_at' => $time,
            'updated_at' => $time,
        ]];
        foreach ($default_values as $value) {
            $found = DB::table('configs')->where('name', $value['name'])->where('module', $value['module'])->first();
            if (!$found) {
                DB::table('configs')->insert([
                    'name' => $value['name'],
                    'module' => $value['module'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class ContextCrsSeveritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'High',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Moderate',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Low',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('context_crs_severities')->where('name', $value['name'])->first();

            if (!$found) {
                DB::table('context_crs_severities')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class EnviaspectProbabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'description' => 'very_rare',
                'weightage' => 0.1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'rare',
                'weightage' => 1.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'low',
                'weightage' => 2.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'moderate',
                'weightage' => 3.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'high',
                'weightage' => 4.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('enviaspect_probabilities')->where('description', $value['description'])->where('weightage', $value['weightage'])->first();

            if (!$found) {
                DB::table('enviaspect_probabilities')->insert([
                    'description' => $value['description'],
                    'weightage' => $value['weightage'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class EnviaspectConsequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'description' => 'negligible_risk',
                'weightage' => 0.1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'very_low_risk',
                'weightage' => 1.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'low_risk',
                'weightage' => 2.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'moderate_risk',
                'weightage' => 3.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'high_risk',
                'weightage' => 4.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('enviaspect_consequences')->where('description', $value['description'])->where('weightage', $value['weightage'])->first();

            if (!$found) {
                DB::table('enviaspect_consequences')->insert([
                    'description' => $value['description'],
                    'weightage' => $value['weightage'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class EnviaspectRegulatoryRequirementSeeder extends Seeder
{
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'description' => 'no_legal_requirement',
                'weightage' => 1.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'legal_requirement',
                'weightage' => 2.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('enviaspect_regulatory_requirements')->where('description', $value['description'])->where('weightage', $value['weightage'])->first();

            if (!$found) {
                DB::table('enviaspect_regulatory_requirements')->insert([
                    'description' => $value['description'],
                    'weightage' => $value['weightage'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }

}

class EnviaspectQuantitySeeder extends Seeder
{
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'description' => 'low',
                'weightage' => 1.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'moderate',
                'weightage' => 2.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'high',
                'weightage' => 3.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'very_high',
                'weightage' => 4.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'excessive',
                'weightage' => 5.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('enviaspect_quantities')->where('description', $value['description'])->where('weightage', $value['weightage'])->first();

            if (!$found) {
                DB::table('enviaspect_quantities')->insert([
                    'description' => $value['description'],
                    'weightage' => $value['weightage'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }

}

class EnviaspectControlMeasureSeeder extends Seeder
{
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'description' => 'effectively_controlled',
                'weightage' => 1.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'ineffective_control',
                'weightage' => 2.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'description' => 'no_control',
                'weightage' => 3.0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('enviaspect_control_measures')->where('description', $value['description'])->where('weightage', $value['weightage'])->first();

            if (!$found) {
                DB::table('enviaspect_control_measures')->insert([
                    'description' => $value['description'],
                    'weightage' => $value['weightage'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'categoryname' => 'External Audit',
                'shortname' => 'has',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('categories')->where('categoryname', $value['categoryname'])->first();
            if (!$found) {
                DB::table('categories')->insert(
                    [
                        'categoryname' => $value['categoryname'],
                        'created_at' => $time,
                        'updated_at' => $time,
                    ]
                );

            }
        }

    }
}

class CompanySizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'compsize' => '300',
                'name' => 'Client Control',
                'email' => 'quickcg@setilas.com',
                'loginsperperson' => '1',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('companysize')->where('compsize', $value['compsize'])->where('name', $value['name'])->where('email', $value['email'])->where('loginsperperson', $value['loginsperperson'])->first();
            if (!$found) {
                DB::table('companysize')->insert(
                    [
                        'compsize' => $value['compsize'],
                        'name' => $value['name'],
                        'email' => $value['email'],
                        'loginsperperson' => $value['loginsperperson'],
                    ]
                );

            }
        }
    }
}

class HmlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'name' => 'High',
            ],
            [
                'name' => 'Moderate',
            ],
            [
                'name' => 'Low',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('hml')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('hml')->insert([
                    'name' => $value['name'],
                ]);
            }
        }

    }
}

class IncidentPreventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'incident_prevent' => 'Stop this activity',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Guard the hazard',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Train the employee(s)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Train the supervisor(s)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Trace the Contacts to avoid further spread',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Redesign the task steps',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Redesign work station',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Write a new policy/rule Enforce existing policy',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Isolate the affected person',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_prevent' => 'Amend the Risk Rigister',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('incident_prevention')->where('incident_prevent', $value['incident_prevent'])->first();
            if (!$found) {
                DB::table('incident_prevention')->insert([
                    'incident_prevent' => $value['incident_prevent'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class IncidentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'incident_type' => 'Fatality',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Disease (Pandemic)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Injure',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Lost Time Injury',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Fire',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Disease (Non Pandemic)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'incident_type' => 'Medical Treatment Case',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('incident_type')->where('incident_type', $value['incident_type'])->first();
            if (!$found) {
                DB::table('incident_type')->insert([
                    [
                        'incident_type' => $value['incident_type'],
                        'created_at' => $time,
                        'updated_at' => $time,
                    ],
                ]);
            }
        }
    }
}
class MailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'driver' => 'smtp',
                'host' => 'smtp.hostinger.in',
                'port' => '587',
                'faddress' => 'admin@setilas.com',
                'fname' => 'admin@setilas.com',
                'username' => 'admin@setilas.com',
                'password' => '=kW7NJ?J',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('mail')->where('driver', $value['driver'])
                ->where('host', $value['host'])
                ->where('port', $value['port'])
                ->where('faddress', $value['faddress'])
                ->where('fname', $value['fname'])
                ->where('username', $value['username'])
                ->where('password', $value['password'])
                ->first();

            if (!$found) {
                DB::table('mail')->insert([
                    'driver' => $value['driver'],
                    'host' => $value['host'],
                    'port' => $value['port'],
                    'faddress' => $value['faddress'],
                    'fname' => $value['fname'],
                    'username' => $value['username'],
                    'password' => $value['password'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class ModenadisableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'objectives' => '1',
                'auditmanagement' => '1',
                'compliance' => '1',
                'riskmanagement' => '1',
                'opportunity' => '1',
                'documentmanagement' => '1',
                'enviaspects' => '1',
                'ohsrisk' => '1',
                'correctiveaction' => '1',
                'auditschedulelogs' => '1',
                'complaintmanagement' => '1',
                'contextmanagement' => '1',
                'vendormanagement' => '1',
                'incidentreport' => '1',
                'nearmiss' => '1',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('modenadisable')->where('objectives', $value['objectives'])
                ->where('auditmanagement', $value['auditmanagement'])
                ->where('compliance', $value['compliance'])
                ->where('riskmanagement', $value['riskmanagement'])
                ->where('opportunity', $value['opportunity'])
                ->where('documentmanagement', $value['documentmanagement'])
                ->where('enviaspects', $value['enviaspects'])
                ->where('ohsrisk', $value['ohsrisk'])
                ->where('correctiveaction', $value['correctiveaction'])
                ->where('auditschedulelogs', $value['auditschedulelogs'])
                ->where('complaintmanagement', $value['complaintmanagement'])
                ->where('contextmanagement', $value['contextmanagement'])
                ->where('vendormanagement', $value['vendormanagement'])
                ->where('incidentreport', $value['incidentreport'])
                ->where('nearmiss', $value['nearmiss'])
                ->first();
            if (!$found) {
                DB::table('modenadisable')->insert([
                    'objectives' => $value['objectives'],
                    'auditmanagement' => $value['auditmanagement'],
                    'compliance' => $value['compliance'],
                    'riskmanagement' => $value['riskmanagement'],
                    'opportunity' => $value['opportunity'],
                    'documentmanagement' => $value['documentmanagement'],
                    'enviaspects' => $value['enviaspects'],
                    'ohsrisk' => $value['ohsrisk'],
                    'correctiveaction' => $value['correctiveaction'],
                    'auditschedulelogs' => $value['auditschedulelogs'],
                    'complaintmanagement' => $value['complaintmanagement'],
                    'contextmanagement' => $value['contextmanagement'],
                    'vendormanagement' => $value['vendormanagement'],
                    'incidentreport' => $value['incidentreport'],
                    'nearmiss' => $value['nearmiss'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class ModpageloadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $count = DB::table('modpageload')->count();
        if (!$count) {
            DB::table('modpageload')->insert([
                'objectives' => '503',
                'auditmanagement' => '669',
                'compliance' => '134',
                'riskmanagement' => '345',
                'opportunity' => '239',
                'documentmanagement' => '398',
                'enviaspects' => '336',
                'ohsrisk' => '208',
                'correctiveaction' => '243',
                'complaintmanagement' => '23',
                'contextmanagement' => '82',
                'vendormanagement' => '53',
                'incidentreport' => '51',
                'nearmiss' => '0',
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

    }
}

class ModuleRegisterPageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'slno',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'id',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>10,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>17,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>21,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>26,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'slno',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'id',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'department',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'responsibility',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'due date',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'process stage',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'is_dragdropable'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action status',
                'column_position'=>9,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'is_dragdropable'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'raisedby',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'division',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'subdivision',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'email remainder for action',
                'column_position'=>13,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'added date',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action reviewed by',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action reviewed date',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'hOD reviewed by',
                'column_position'=>17,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'hOD reviewed date',
                'column_position'=>18,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'closure reviewed by',
                'column_position'=>19,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'closure reviewed date',
                'column_position'=>20,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'S.No',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'tracking number',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer',
                'column_position'=>5,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'due date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'raisedby',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'added date',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received by',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'mode of complaint',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer phone',
                'column_position'=>14,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer email',
                'column_position'=>15,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'product',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'sale order no.',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery no.',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery date',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'plant',
                'column_position'=>20,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'vendor',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'division',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'subdivision',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'financial loss',
                'column_position'=>24,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'potential cost saving',
                'column_position'=>25,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'email reminder for action',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'investigation team lead',
                'column_position'=>27,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'investigation target date',
                'column_position'=>28,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed by',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed date',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed by',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed date',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'process stage',
                'column_position'=>33,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'action status',
                'column_position'=>34,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'S.No',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'tracking number',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer',
                'column_position'=>5,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'due date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'raisedby',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'added date',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received by',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'mode of complaint',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer phone',
                'column_position'=>14,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer email',
                'column_position'=>15,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'job order no.',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product serial no.',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'project name',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'warranty coverage',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'plant',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint category',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'vendor',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'division',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'subdivision',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'financial loss',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'potential cost saving',
                'column_position'=>27,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'email reminder for action',
                'column_position'=>28,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation team lead',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation target date',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed by',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed date',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed by',
                'column_position'=>33,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed date',
                'column_position'=>34,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'process stage',
                'column_position'=>35,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'action status',
                'column_position'=>36,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('module_register_page_details')
                            ->where('module_name', $value['module_name'])
                            ->where('column_name', $value['column_name'])
                            ->first();
            if (!$found) {
                DB::table('module_register_page_details')->insert([
                    'module_name' => $value['module_name'],
                    'column_name' => $value['column_name'],
                    'column_position' => $value['column_position'],
                    'column_filter' => $value['column_filter'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class ModuleMyOpenPageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'slno',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'id',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>9,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('module_my_open_page_details')
                            ->where('module_name', $value['module_name'])
                            ->where('column_name', $value['column_name'])
                            ->first();
            if (!$found) {
                DB::table('module_my_open_page_details')->insert([
                    'module_name' => $value['module_name'],
                    'column_name' => $value['column_name'],
                    'column_position' => $value['column_position'],
                    'column_filter' => $value['column_filter'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class ModuleClosedPageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>10,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>17,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>21,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>26,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'tracking number',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'title',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'type',
                'column_position'=>3,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'department',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'responsibility',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'due date',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'issue authenticity',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'product',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'process stage',
                'column_position'=>10,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'action status',
                'column_position'=>11,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'raised by',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received by',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received date',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'mode of complaint',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer phone',
                'column_position'=>16,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer email',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'sale order no.',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery no.',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery date',
                'column_position'=>20,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'plant',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'vendor',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'division',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'subdivision',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'financial loss',
                'column_position'=>24,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'potential cost saving',
                'column_position'=>25,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'email reminder for action',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'investigation team lead',
                'column_position'=>27,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            // [
            //     'module_name' => 'complaint',
            //     'column_name'=>'investigation target date',
            //     'column_position'=>28,
            //     'column_filter'=>1,
            //     'is_active'=>0,
            //     'enabled_by_user'=>0,
            //     'created_at' => $time,
            //     'updated_at' => $time,
            // ],
            [
                'module_name' => 'complaint',
                'column_name'=>'added date',
                'column_position'=>28,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed by',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed date',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed by',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed date',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'slno',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'is_dragdropable'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'id',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'is_dragdropable'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'is_dragdropable'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'department',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'responsibility',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'due date',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'process stage',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'is_dragdropable'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action status',
                'column_position'=>9,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'is_dragdropable'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'raisedby',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'division',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'subdivision',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'email remainder for action',
                'column_position'=>13,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'added date',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action reviewed by',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'action reviewed date',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'hOD reviewed by',
                'column_position'=>17,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'hOD reviewed date',
                'column_position'=>18,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'closure reviewed by',
                'column_position'=>19,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'audit',
                'column_name'=>'closure reviewed date',
                'column_position'=>20,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'tracking number',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'type',
                'column_position'=>4,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer',
                'column_position'=>5,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'due date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'raisedby',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'added date',
                'column_position'=>10,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received by',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'mode of complaint',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer phone',
                'column_position'=>14,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer email',
                'column_position'=>15,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product',
                'column_position'=>16,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'job order no.',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product serial no.',
                'column_position'=>18,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'project name',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'warranty coverage',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'plant',
                'column_position'=>21,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint category',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'vendor',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'division',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'subdivision',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'financial loss',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'potential cost saving',
                'column_position'=>27,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'email reminder for action',
                'column_position'=>28,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation team lead',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation target date',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'issue authenticity',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'source of issue',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed by',
                'column_position'=>33,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed date',
                'column_position'=>34,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed by',
                'column_position'=>35,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed date',
                'column_position'=>36,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'process stage',
                'column_position'=>37,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'action status',
                'column_position'=>38,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('module_closed_page_details')
                            ->where('module_name', $value['module_name'])
                            ->where('column_name', $value['column_name'])
                            ->first();
            if (!$found) {
                DB::table('module_closed_page_details')->insert([
                    'module_name' => $value['module_name'],
                    'column_name' => $value['column_name'],
                    'column_position' => $value['column_position'],
                    'column_filter' => $value['column_filter'],
                    'is_active' => $value['is_active'],
                    'is_dragdropable' => isset($value['is_dragdropable']) ? $value['is_dragdropable'] : 1,
                    'enabled_by_user' => $value['enabled_by_user'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class CategoryBasedFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = now();
        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'job order number',
                'field_name'=>'job_order_number',
                'column_position'=>51,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'product serial number',
                'field_name'=>'product_serial_number',
                'column_position'=>52,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'product name',
                'field_name'=>'product_name',
                'column_position'=>53,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'product code',
                'field_name'=>'product_code',
                'column_position'=>54,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'batch',
                'field_name'=>'batch',
                'column_position'=>55,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'production line',
                'field_name'=>'production_line',
                'column_position'=>56,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'date of manufacture',
                'field_name'=>'date_of_manufacture',
                'column_position'=>57,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'quantity affected',
                'field_name'=>'quantity_affected',
                'column_position'=>58,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'product ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'job order number',
                'field_name'=>'job_order_number',
                'column_position'=>59,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'product serial number',
                'field_name'=>'product_serial_number',
                'column_position'=>60,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process name',
                'field_name'=>'process_name',
                'column_position'=>61,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'machine / equipment id',
                'field_name'=>'machine_equipment_id',
                'column_position'=>62,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'operator / shift',
                'field_name'=>'operator_shift',
                'column_position'=>63,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'production line',
                'field_name'=>'production_line',
                'column_position'=>64,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'date of process',
                'field_name'=>'date_of_process',
                'column_position'=>65,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'quantity affected',
                'field_name'=>'quantity_affected',
                'column_position'=>66,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'process ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'job order number',
                'field_name'=>'job_order_number',
                'column_position'=>67,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'product serial number',
                'field_name'=>'product_serial_number',
                'column_position'=>68,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'vendor name',
                'field_name'=>'vendor_name',
                'column_position'=>69,
                'column_filter'=>1,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'item serial number',
                'field_name'=>'item_serial_number',
                'column_position'=>80,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'purchase order (PO) number',
                'field_name'=>'purchase_order_number',
                'column_position'=>81,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'receiving report (RR) number',
                'field_name'=>'receiving_report_number',
                'column_position'=>82,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'delivery date',
                'field_name'=>'delivery_date',
                'column_position'=>83,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'quantity affected',
                'field_name'=>'quantity_affected',
                'column_position'=>84,
                'column_filter'=>0,
                'is_active'=>1,
                'category_name' => 'vendor ncr',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('category_based_fields')
                            ->where('module_name', $value['module_name'])
                            ->where('column_name', $value['column_name'])
                            ->where('category_name', $value['category_name'])
                            ->first();
            if (!$found) {
                DB::table('category_based_fields')->insert([
                    'module_name' => $value['module_name'],
                    'column_name' => $value['column_name'],
                    'field_name' => $value['field_name'],
                    'column_position' => $value['column_position'],
                    'column_filter' => $value['column_filter'],
                    'category_name' => $value['category_name'],
                    'is_active' => $value['is_active'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class ModuleBladePageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'page_name'=>'Add Report Details',
                'group_name' => 'Register Page',
                'blade_path'=>'preview/calibration/internal',
                'route_path' => 'user/calibration/internal',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11'
                ],
                'structure' => 'col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Update Calibration Status',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration.updatestatus',
                'route_path' => 'user/calibration/viewstatus',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                ],
                'structure' => 'col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'page_name'=>'View Details Page',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration._viewdetails',
                'route_path' => 'user/calibrationmgmt',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                    'field_16'=> '16',
                    'field_17'=> '17',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'View Details Page',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration._viewdetails',
                'route_path' => 'user/calibrationmgmt',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                    'field_16'=> '16',
                    'field_17'=> '17',
                    'field_18'=> '18',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'group_name' => 'Register Page',
                'page_name'=>'Edit Instrument Details',
                'blade_path'=>'preview.calibration.editinstruments',
                'route_path' => 'user/calibration/editins',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'group_name' => 'Register Page',
                'page_name'=>'Edit Instrument Details',
                'blade_path'=>'preview.calibration.editinstruments',
                'route_path' => 'user/calibration/editins',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                    'field_16'=> '16',
                    'field_17'=> '17',
                    'field_18'=> '18',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Add Attachments',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration.__addexternalattachments',
                'route_path' => 'user/calibrationmgmt',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => ['field_1' => '1'],
                'structure' => 'col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'page_name'=>'Internal Calibration Report',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration.displayreport',
                'route_path' => 'user/calibration/disprep',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'page_name'=>'Calibration Log',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration.report',
                'route_path' => 'user/calibration/cal_log',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Calibration Log',
                'group_name' => 'Register Page',
                'blade_path'=>'preview.calibration.externalreport',
                'route_path' => 'user/calibration/cal_logext',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'group_name' => 'Under Calibration',
                'page_name'=>'Edit Instrument Details',
                'blade_path'=>'preview.calibration.editinstruments',
                'route_path' => 'user/calibration/editins',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                    'field_16'=> '16',
                    'field_17'=> '17',
                    'field_18'=> '18',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Change Status',
                'group_name' => 'Under Calibration',
                'blade_path'=>'preview.calibration.update_status_form',
                'route_path' => 'user/calibration/update-status-form',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                ],
                'structure' => 'col-4'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Calibration Log',
                'group_name' => 'Under Calibration',
                'blade_path'=>'preview.calibration.externalreport',
                'route_path' => 'user/calibration/cal_logext',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'View Details Page',
                'group_name' => 'Under Calibration',
                'blade_path'=>'preview.calibration._underviewdetails',
                'route_path' => 'user/calibration/under-list',
                'is_display_page'=>1,
                'is_target_page'=>0,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                    'field_16'=> '16',
                    'field_17'=> '17',
                ],
                'structure' => 'table-col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'page_name'=>'Add Attachments',
                'group_name' => 'Under Calibration',
                'blade_path'=>'preview.calibration.__addexternalattachments',
                'route_path' => 'user/calibration/under-list',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => ['field_1'=>'1'],
                'structure' => 'col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'external',
                'group_name' => 'Add Instrument',
                'page_name'=>'Add Instrument Page',
                'blade_path'=>'preview.calibration.addinstruments',
                'route_path' => 'user/calibration/addinstruments',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                    'field_14'=> '14',
                    'field_15'=> '15',
                ],
                'structure' => 'col-2'
            ],
            [
                'module_name' => 'calibration',
                'sub_module' => 'internal',
                'group_name' => 'Add Instrument',
                'page_name'=>'Add Instrument Page',
                'blade_path'=>'preview.calibration.addinstruments',
                'route_path' => 'user/calibration/addinstruments',
                'is_display_page'=>0,
                'is_target_page'=>1,
                'field_index' => [
                    'field_1'=>'1',
                    'field_2'=> '2',
                    'field_3'=> '3',
                    'field_4'=> '4',
                    'field_5'=> '5',
                    'field_6'=> '6',
                    'field_7'=> '7',
                    'field_8'=> '8',
                    'field_9'=> '9',
                    'field_10'=> '10',
                    'field_11'=> '11',
                    'field_12'=> '12',
                    'field_13'=> '13',
                ],
                'structure' => 'col-2'
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('module_blade_page_details')
                            ->where('module_name', $value['module_name'])
                            ->where('page_name', $value['page_name'])
                            ->where('sub_module', isset($value['sub_module'])?$value['sub_module']:null)
                            ->where('group_name', $value['group_name'])
                            ->first();
            if (!$found) {
                DB::table('module_blade_page_details')->insert([
                    'module_name' => $value['module_name'],
                    'sub_module' => isset($value['sub_module'])?$value['sub_module']:null,
                    'group_name' => $value['group_name'],
                    'page_name' => $value['page_name'],
                    'blade_path' => $value['blade_path'],
                    'route_path' => $value['route_path'],
                    'is_display_page' => $value['is_display_page'],
                    'is_target_page' => $value['is_target_page'],
                    'field_index' => json_encode($value['field_index']),
                    'structure' => $value['structure'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class PartiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'type' => 'Internal',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'type' => 'External',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('parties')->where('type', $value['type'])->first();
            if (!$found) {
                DB::table('parties')->insert([
                    [
                        'type' => $value['type'],
                        'created_at' => $time,
                        'updated_at' => $time,
                    ],
                ]);
            }
        }

    }
}

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'None',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'High',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Moderate',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Low',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Urgent',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Immediate',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('priorities')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('priorities')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class ProtectionKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'kit' => 'Helmet',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'kit' => 'Hand Glove',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'kit' => 'Safety Shoe',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'kit' => 'Safety Goggles',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('protection_kit')->where('kit', $value['kit'])->first();
            if (!$found) {
                DB::table('protection_kit')->insert([
                    'kit' => $value['kit'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class RiskNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'riskgroups' => 'Strategic',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'riskgroups' => 'Operational',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'riskgroups' => 'Financial',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'riskgroups' => 'Compliance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('risknames')->where('riskgroups', $value['riskgroups'])->first();
            if (!$found) {
                DB::table('risknames')->insert([
                    [
                        'riskgroups' => $value['riskgroups'],
                        'created_at' => $time,
                        'updated_at' => $time,
                    ],
                ]);
            }
        }

    }
}

class RiskStrategiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Mitigate',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Avoid',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Accept',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Share',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Transfer',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('riskstrategies')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('riskstrategies')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class RiskGroupDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Competitive Risk',
                'risk_desp' => 'The risk of losing market share or competitive advantage due to the actions of competitors or new market entrants.',
                'risk_driver' => 'Market saturation and intense competition
                                      Entry of new competitors with disruptive business models or innovative offerings
                                      Price wars or aggressive marketing tactics from competitors
                                      Loss of key customers or contracts to competitors',
                'mitigating_actions' => 'Conduct regular market research to stay updated on competitors activities and market trends.
                                             Differentiate products or services by focusing on unique value propositions or niche markets.
                                             Implement effective marketing and promotional strategies to build brand awareness and customer loyalty.
                                             Continuously monitor and adapt pricing strategies to remain competitive.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Technological Risk',
                'risk_desp' => 'The risk of being unable to keep up with technological advancements or failing to adopt emerging technologies that could impact the organizations operations, efficiency, or product/service offerings.',
                'risk_driver' => 'Rapid technological advancements and industry innovation
                                      Inadequate investment in research and development (R&D)
                                      Lack of technological expertise or resistance to change within the organization
                                      Failure to adopt or leverage emerging technologies effectively',
                'mitigating_actions' => 'Establish an ongoing technology assessment process to identify and adopt relevant emerging technologies.
                                             Invest in research and development (R&D) to enhance existing products/services and develop innovative solutions.
                                             Foster a culture of innovation and provide training to employees to embrace technological advancements.
                                             Collaborate with technology partners or engage in strategic alliances to leverage expertise and resources.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Reputational Risk',
                'risk_desp' => 'The risk of damage to the organizations reputation due to negative public perception, customer dissatisfaction, unethical behavior, or public relations issues.',
                'risk_driver' => 'Negative customer experiences, product recalls, or quality issues
                                      Mishandling of public relations or communication crises
                                      Unethical behavior or corporate scandals
                                      Social media backlash or negative online reviews',
                'mitigating_actions' => 'Develop and enforce ethical conduct policies to ensure adherence to high ethical standards.
                                             Implement effective customer relationship management strategies to enhance customer satisfaction.
                                             Monitor and actively engage in managing online reputation through social media and online review platforms.
                                            Establish crisis management plans and communication protocols to handle reputational issues promptly and transparently.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Regulatory and Compliance Risk',
                'risk_desp' => 'The risk of non-compliance with laws, regulations, or industry standards, which may lead to penalties, fines, legal action, or damage to the organizations reputation',
                'risk_driver' => 'Evolving and complex regulatory requirements and standards
                                      Incomplete understanding or misinterpretation of regulations
                                      Inadequate compliance training and monitoring
                                      Non-compliance with industry-specific or international standards',
                'mitigating_actions' => 'Stay updated on applicable laws, regulations, and industry standards through continuous monitoring.
                                             Implement robust compliance programs with clear policies, training, and monitoring mechanisms.
                                             Conduct regular internal audits to identify and address compliance gaps or potential risks.
                                             Foster a culture of compliance and ethics throughout the organization.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Financial Risk',
                'risk_desp' => 'The risk of financial instability, such as cash flow issues, inability to meet financial obligations, or economic downturns that may affect profitability and sustainability.',
                'risk_driver' => 'Volatile economic conditions or market fluctuations
                                      Inadequate cash flow management or high debt levels
                                      Lack of diversification in revenue streams
                                      Poor financial planning or forecasting',
                'mitigating_actions' => 'Maintain accurate and up-to-date financial records for effective financial planning and forecasting.
                                             Diversify revenue streams to reduce reliance on a single market or customer segment.
                                             Implement cash flow management strategies to ensure adequate liquidity and minimize financial risks.
                                             Regularly assess and mitigate risks related to currency fluctuations and interest rate changes through hedging or appropriate financial instruments.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Operational Risk',
                'risk_desp' => 'The risk of disruption or failure in the organizations operational processes, including supply chain disruptions, equipment failure, cybersecurity threats, or inadequate business continuity planning.',
                'risk_driver' => 'Inefficient or outdated operational processes
                                      Supply chain disruptions or dependency on single suppliers
                                      Inadequate cybersecurity measures or data breaches
                                      Lack of robust business continuity planning',
                'mitigating_actions' => 'Develop and implement robust operational processes and systems to ensure efficiency and reliability.
                                             Establish business continuity plans to mitigate the impact of potential disruptions.
                                             Invest in cybersecurity measures to protect against data breaches and other cyber threats.
                                             Enhance supply chain management practices to reduce the risk of disruptions and develop alternative sourcing options.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Market Risk',
                'risk_desp' => 'The risk of changes in market conditions, customer preferences, or demand patterns that could impact sales, pricing, or product/service viability.',
                'risk_driver' => 'Shifting consumer preferences or changing market dynamics
                                      Disruption from new technologies or alternative products/services
                                      Economic downturns or recessions affecting purchasing power
                                      Fluctuating demand patterns or seasonal variations',
                'mitigating_actions' => 'Conduct thorough market analysis to identify shifts in consumer preferences and market trends.
                                            Develop a flexible product/service portfolio to adapt to changing market dynamics.
                                            Establish strong customer relationships through personalized offerings, exceptional service, or loyalty programs.
                                            Diversify target markets to reduce reliance on a single geographic region or customer segment.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Strategic Decision Risk',
                'risk_desp' => 'The risk associated with making poor strategic decisions, including ineffective market positioning, incorrect resource allocation, or pursuing unsustainable business models.',
                'risk_driver' => 'Insufficient market research or analysis before making strategic decisions
                                     Overestimation of market demand or underestimation of competition
                                     Lack of flexibility or failure to adapt to changing circumstances
                                     Ineffective resource allocation or poor strategic execution',
                'mitigating_actions' => 'Conduct comprehensive market research and analysis before making strategic decisions.
                                            Seek input from diverse stakeholders and subject matter experts during the decision-making process.
                                            Regularly review and assess the effectiveness of strategic decisions, adjusting as necessary.
                                            Implement effective project management practices to ensure strategic initiatives are executed successfully.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Talent and Human Resources Risk',
                'risk_desp' => 'The risk of inadequate talent acquisition, retention, or development, which may impact the organizations ability to execute its strategy or adapt to changing needs.',
                'risk_driver' => 'Difficulty in attracting and retaining skilled employees
                                     Inadequate succession planning or talent development programs
                                     Lack of employee engagement or organizational culture issues
                                     Skills gaps or mismatch between workforce capabilities and business needs',
                'mitigating_actions' => 'Develop a comprehensive talent acquisition and retention strategy, including competitive compensation and benefits packages.
                                            Provide ongoing training and development programs to enhance employee skills and capabilities.
                                            Foster a positive and engaging work culture to attract and retain top talent.
                                            Succession planning and knowledge transfer initiatives to mitigate the risk of key personnel leaving the organization.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Environmental and Sustainability Risk',
                'risk_desp' => 'The risk of environmental factors, climate change, or sustainability issues impacting the organizations operations, reputation, or regulatory compliance.',
                'risk_driver' => 'Climate change and natural disasters impacting operations
                                     Regulatory changes related to environmental standards
                                     Failure to adopt sustainable practices or meet environmental goals
                                     Reputational damage due to perceived environmental irresponsibility',
                'mitigating_actions' => 'Adopt environmentally friendly practices and technologies to minimize environmental impact.
                                            Comply with relevant environmental regulations and standards.
                                            Invest in sustainable supply chain management practices and collaborate with eco-conscious suppliers.
                                            Communicate sustainability efforts transparently to build trust with stakeholders.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Innovation Risk',
                'risk_desp' => 'The risk of failing to innovate or adapt to changing customer needs, emerging technologies, or disruptive business models, which can lead to a loss of market share or competitive advantage.',
                'risk_driver' => 'Inadequate investment in research and development (R&D)
                                     Failure to monitor and adapt to emerging technologies or market trends
                                     Lack of a culture of innovation or resistance to change within the organization
                                     Inability to effectively commercialize or implement innovative ideas',
                'mitigating_actions' => 'Foster a culture of innovation by encouraging idea generation and experimentation.
                                            Allocate dedicated resources and budgets for research and development (R&D) activities.
                                            Establish cross-functional teams to promote collaboration and idea sharing.
                                            Monitor market trends and emerging technologies to identify opportunities for innovation.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Economic Risk',
                'risk_desp' => 'The risk of economic factors, such as inflation, recession, currency fluctuations, or changes in interest rates, impacting the organizations financial performance, demand for products/services, or investment decisions.',
                'risk_driver' => 'Economic downturns or recessions impacting consumer spending
                                     Fluctuations in currency exchange rates affecting import/export costs
                                     Inflationary pressures or rising raw material prices
                                     Interest rate changes impacting borrowing costs or investment decisions',
                'mitigating_actions' => 'Maintain a diverse customer base and explore new markets to mitigate reliance on a single market.
                                            Develop flexible pricing strategies to adjust to economic fluctuations.
                                            Implement robust financial planning and forecasting processes to anticipate economic risks.
                                            Establish contingency plans to adapt quickly to changing economic conditions.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Ethical and Compliance Risk',
                'risk_desp' => 'The risk associated with unethical behavior, non-compliance with ethical standards, or violations of corporate governance principles, which can damage the organizations reputation, stakeholder trust, and legal standing.',
                'risk_driver' => 'Lack of clear ethical guidelines or code of conduct
                                     Non-compliance with corporate governance principles or ethical standards
                                     Corruption, bribery, or fraudulent activities within the organization
                                     Poor risk culture or weak internal controls leading to unethical behavior',
                'mitigating_actions' => 'Develop and enforce a comprehensive code of conduct and ethical guidelines.
                                            Conduct regular ethics training and awareness programs for employees.
                                            Establish anonymous reporting mechanisms for reporting unethical behavior or compliance violations.
                                            Implement internal controls and audits to ensure compliance with relevant laws and regulations.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Supply Chain Risk',
                'risk_desp' => 'The risk of disruptions in the supply chain, including supplier failures, logistics challenges, natural disasters, or political unrest, which can impact production, delivery timelines, and customer satisfaction.',
                'risk_driver' => 'Dependency on a single or limited number of suppliers
                                     Supply chain disruptions from natural disasters, labor strikes, or geopolitical events
                                     Inadequate supplier risk assessment or due diligence processes
                                     Poor visibility and transparency across the supply chain',
                'mitigating_actions' => 'Perform regular risk assessments of the supply chain to identify vulnerabilities.
                                            Diversify the supplier base and establish alternative sourcing options.
                                            Establish strong relationships with key suppliers and conduct regular performance evaluations.
                                            Develop contingency plans to address potential disruptions in the supply chain.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Strategic',
                'risk_name' => 'Cybersecurity and Data Privacy Risk',
                'risk_desp' => 'The risk of cyber-attacks, data breaches, or unauthorized access to sensitive information, which can result in financial losses, legal penalties, reputational harm, or compromised customer trust.',
                'risk_driver' => 'Increasing sophistication of cyber threats and hacking attempts
                                     Insufficient investment in cybersecurity measures and employee training
                                     Weak data protection policies or vulnerabilities in IT infrastructure
                                     Non-compliance with data privacy regulations or data breaches',
                'mitigating_actions' => 'Implement robust cybersecurity measures, including firewalls, encryption, and intrusion detection systems.
                                            Conduct regular security audits and penetration testing to identify vulnerabilities.
                                            Train employees on cybersecurity best practices and promote a culture of data protection.
                                            Develop incident response plans to effectively handle data breaches or cyber-attacks.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Regulatory non-compliance',
                'risk_desp' => 'Failure to adhere to laws and regulations relevant to the organizations industry or jurisdiction, such as data protection, environmental, financial, or safety regulations.',
                'risk_driver' => 'Lack of awareness: Inadequate understanding of relevant laws and regulations due to limited training or knowledge-sharing mechanisms.
                                     Complexity and frequent changes in regulations: Inability to keep up with evolving regulatory requirements due to their complexity and rapid updates.
                                     Inadequate compliance monitoring and oversight: Insufficient systems and controls to effectively monitor and enforce compliance with applicable regulations.
                                     Insufficient training and education: Lack of comprehensive training programs to educate employees on compliance obligations and best practices.
                                     Inconsistent interpretation and application of regulations: Varied interpretations and inconsistent practices across different departments or regions.',
                'mitigating_actions' => 'Increase awareness through regular training sessions and knowledge-sharing platforms.
                                            Stay updated on regulatory changes through dedicated personnel and robust tracking systems.
                                            Enhance compliance monitoring and oversight through clear roles and regular audits.
                                            Improve training and education programs to ensure understanding of compliance obligations.
                                            Establish clear policies and conduct training to promote consistent interpretation and application of regulations.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Lack of internal controls',
                'risk_desp' => 'Inadequate or ineffective internal controls and monitoring processes can lead to compliance failures, such as improper handling of sensitive information, unauthorized access to data, or fraud.',
                'risk_driver' => 'Inadequate control frameworks: Absence of robust internal control mechanisms to prevent compliance failures, such as inadequate segregation of duties or lack of access controls.
                                     Weak monitoring processes: Ineffective monitoring of internal controls, leading to undetected control deficiencies and potential compliance breaches.
                                     Insufficient documentation and review: Lack of proper documentation and regular review of internal control processes and procedures.',
                'mitigating_actions' => 'Establish robust internal control mechanisms, including proper segregation of duties and access controls.
                                            Implement a comprehensive monitoring system to regularly evaluate the effectiveness of internal controls.
                                            Develop standardized documentation procedures for internal control processes and regularly review and update them.
                                            Conduct periodic internal audits to identify control deficiencies and take corrective actions.
                                            Provide adequate training to employees responsible for monitoring and ensure they have the necessary skills and tools.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Inaccurate or incomplete reporting',
                'risk_desp' => 'Failure to provide accurate and timely reports to regulatory bodies, shareholders, or other stakeholders, including financial statements, audit reports, or compliance documentation.',
                'risk_driver' => 'Inadequate reporting systems: Absence of well-established reporting systems and processes to ensure accurate and timely reporting.
                                      Poor data management: Inefficiencies in data collection, validation, and consolidation, leading to inaccuracies in reported information.
                                      Lack of internal coordination: Insufficient coordination among departments involved in reporting, resulting in delays or omissions in reporting requirements.',
                'mitigating_actions' => 'Implement well-established reporting systems and processes to ensure accuracy and timeliness of reporting.
                                            Improve data management practices, including efficient data collection, validation, and consolidation processes.
                                            Enhance internal coordination among departments involved in reporting to minimize delays and ensure compliance with reporting requirements.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Insider trading',
                'risk_desp' => 'Unauthorized trading of company securities based on non-public, material information, which violates securities laws and regulations.',
                'risk_driver' => 'Lack of internal controls: Weak internal controls and monitoring processes to detect and prevent unauthorized trading based on non-public information.
                                     Information leakage: Inadequate safeguards to prevent the unauthorized disclosure of sensitive or material information.',
                'mitigating_actions' => 'Strengthen internal controls to detect and prevent unauthorized trading based on non-public information.
                                            Implement strict access controls and confidentiality measures to prevent information leakage.
                                            Provide comprehensive training on insider trading policies and ensure employees are aware of their responsibilities and legal obligations.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Money laundering and terrorist financing',
                'risk_desp' => 'Failure to implement robust anti-money laundering (AML) and counter-terrorism financing (CTF) controls, including customer due diligence, suspicious transaction reporting, and monitoring for unusual activities.',
                'risk_driver' => 'Inadequate AML and CTF controls: Failure to implement comprehensive anti-money laundering and counter-terrorism financing controls, including customer due diligence and transaction monitoring.
                                     Weak monitoring for unusual activities: Ineffective systems to identify and report suspicious transactions or activities.',
                'mitigating_actions' => 'Implement robust AML and CTF controls, including customer due diligence procedures and transaction monitoring systems.
                                            Enhance monitoring capabilities to identify and report unusual or suspicious transactions or activities.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Bribery and corruption',
                'risk_desp' => 'Failure to prevent bribery, kickbacks, or other forms of corrupt practices, both domestically and internationally, in violation of anti-corruption laws such as the Foreign Corrupt Practices Act (FCPA) or the UK Bribery Act.',
                'risk_driver' => 'Inadequate anti-corruption measures: Insufficient policies, procedures, and controls to prevent and detect bribery and corruption, both domestically and internationally.
                                     Lack of due diligence: Failure to conduct thorough due diligence on third parties and business partners, increasing the risk of involvement in corrupt practices.',
                'mitigating_actions' => 'Develop and enforce comprehensive anti-corruption policies, procedures, and controls.
                                            Conduct thorough due diligence on third parties and business partners to mitigate the risk of involvement in corrupt practices.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Employment law violations',
                'risk_desp' => 'Non-compliance with employment laws and regulations, including improper documentation, discrimination, harassment, unfair labor practices, or violations of minimum wage and working hour requirements.',
                'risk_driver' => 'Incomplete or improper documentation: Inaccurate or missing employee records and contracts, leading to non-compliance with employment laws and regulations.
                                     Inadequate HR policies and practices: Insufficient policies and practices to address workplace discrimination, harassment, or violations of wage and hour requirements.',
                'mitigating_actions' => 'Ensure accurate and proper documentation of employee records and contracts.
                                            Establish and enforce HR policies and practices that comply with employment laws, addressing discrimination, harassment, and wage and hour requirements.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Data protection and privacy breaches',
                'risk_desp' => 'Failure to protect personal and sensitive data, including unauthorized access, data breaches, inadequate cybersecurity measures, or non-compliance with data protection regulations like the General Data Protection Regulation (GDPR) or the California Consumer Privacy Act (CCPA).',
                'risk_driver' => 'Inadequate cybersecurity measures: Insufficient safeguards to protect personal and sensitive data from unauthorized access or breaches.
                                     Non-compliance with data protection regulations: Failure to adhere to data protection laws and regulations, resulting in potential privacy breaches and regulatory penalties.',
                'mitigating_actions' => 'Implement robust cybersecurity measures to protect personal and sensitive data from unauthorized access and breaches.
                                            Comply with data protection regulations and establish processes to ensure data privacy and secure handling of information.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Failure to maintain ethical standards',
                'risk_desp' => 'Violations of codes of conduct, conflicts of interest, improper gifts and entertainment practices, or other ethical breaches that can damage an organizations reputation.',
                'risk_driver' => 'Weak ethical guidelines and enforcement: Inadequate codes of conduct, conflict of interest policies, or measures to prevent improper gifts and entertainment practices.
                                     Lack of ethical awareness and training: Insufficient efforts to educate employees on ethical standards and foster a culture of integrity.',
                'mitigating_actions' => 'Strengthen ethical guidelines and enforce compliance with codes of conduct and conflict of interest policies.
                                            Provide ethical awareness training to employees and promote a culture of integrity and ethical behavior.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Compliance',
                'risk_name' => 'Third-party risks',
                'risk_desp' => 'Failure to properly manage and monitor the compliance of third-party vendors, suppliers, contractors, or business partners, which can lead to regulatory violations or reputational damage.',
                'risk_driver' => 'Inadequate due diligence on vendors and business partners: Insufficient assessment of third-party compliance with relevant laws and regulations.
                                     Weak monitoring of third-party activities: Lack of effective controls and oversight over third-party activities, increasing the risk of non-compliance.',
                'mitigating_actions' => 'Conduct thorough due diligence on vendors and business partners to assess their compliance with relevant laws and regulations.
                                            Implement effective monitoring and oversight mechanisms to mitigate risks associated with third-party activities.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Market risk',
                'risk_desp' => 'The risk of financial losses due to changes in market conditions, such as fluctuations in interest rates, exchange rates, commodity prices, or stock market movements.',
                'risk_driver' => 'Economic factors: Changes in economic conditions, such as GDP growth, inflation rates, or geopolitical events, impacting market dynamics.
                                     Volatility: High levels of price volatility and market uncertainty affecting asset values and investment returns.
                                     Interest rate movements: Fluctuations in interest rates impacting borrowing costs, investment returns, and debt refinancing.',
                'mitigating_actions' => 'Diversify investment portfolio to spread risk across different asset classes and geographic regions.
                                            Regularly monitor and analyze market trends, economic indicators, and geopolitical events to make informed investment decisions.
                                            Implement hedging strategies, such as options or futures contracts, to protect against adverse market movements.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Credit risk',
                'risk_desp' => 'The risk of financial losses arising from the failure of counterparties to fulfill their contractual obligations, including defaults on loans, trade receivables, or investments.',
                'risk_driver' => 'Counterparty creditworthiness: Assessment of the creditworthiness and financial stability of borrowers, customers, or trading partners.
                                     Default risk: Probability of counterparties failing to meet their payment obligations, resulting in financial losses.
                                     Credit underwriting practices: Adequacy of credit assessment processes, including risk analysis, collateral evaluation, and credit limits.',
                'mitigating_actions' => 'Conduct thorough credit assessments and due diligence on counterparties before extending credit or entering into financial transactions.
                                            Establish credit limits and monitor exposure to individual counterparties to manage credit risk concentration.
                                            Implement credit risk management tools, such as credit insurance or guarantees, to mitigate potential losses from default.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Liquidity risk',
                'risk_desp' => 'The risk of not being able to meet short-term financial obligations or fund operations due to a shortage of available cash or liquid assets.',
                'risk_driver' => 'Cash flow management: Effectiveness of cash flow forecasting, working capital management, and access to emergency funding.
                                     Asset liquidity: Ability to convert assets into cash quickly without significant loss of value.
                                     Funding sources: Diversification and availability of funding sources to meet short-term obligations.',
                'mitigating_actions' => 'Maintain adequate cash reserves and establish contingency funding plans to address unexpected liquidity needs.
                                            Establish lines of credit or access to alternative funding sources to ensure sufficient liquidity during periods of financial stress.
                                            Regularly assess and manage working capital requirements to optimize cash flow and minimize liquidity risk.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Operational risk',
                'risk_desp' => 'The risk of financial losses resulting from inadequate or failed internal processes, systems, or human error, including fraud, IT failures, supply chain disruptions, or legal and regulatory compliance failures.',
                'risk_driver' => 'Internal processes and controls: Efficiency and effectiveness of operational processes, internal controls, and risk management frameworks.
                                     Technology and cybersecurity: Adequacy of IT infrastructure, data security measures, and protection against cyber threats.
                                     Human error and misconduct: Potential errors, omissions, or fraudulent activities by employees or third parties.',
                'mitigating_actions' => 'Implement robust internal controls, policies, and procedures to mitigate operational risks and ensure proper risk management practices.
                                            Provide regular training and awareness programs to employees to minimize human errors and promote a culture of risk awareness.
                                            Implement robust cybersecurity measures, including firewalls, encryption, and access controls, to protect against cyber threats.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Foreign exchange risk',
                'risk_desp' => 'The risk of financial losses due to fluctuations in exchange rates when conducting business transactions in different currencies.',
                'risk_driver' => 'Currency exposure: Level of exposure to foreign currency fluctuations in international transactions or investments.
                                     Hedging practices: Utilization of hedging instruments, such as forward contracts or currency options, to mitigate currency risk.
                                     Country-specific risks: Political instability, regulatory changes, or economic conditions impacting exchange rates.',
                'mitigating_actions' => 'Identify and quantify foreign exchange exposure in international transactions and implement hedging strategies to mitigate currency risk.
                                            Diversify currency holdings and consider natural hedging through matching foreign currency revenues and expenses.
                                            Stay updated on global economic and political developments to anticipate potential currency fluctuations and their impact.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Interest rate risk',
                'risk_desp' => 'The risk of financial losses resulting from changes in interest rates, particularly for organizations with variable-rate debt or investments.',
                'risk_driver' => 'Interest rate exposure: Sensitivity of financial assets or liabilities to changes in interest rates.
                                     Interest rate hedging: Usage of interest rate derivatives or other hedging strategies to manage interest rate risk.
                                     Duration mismatch: Mismatch between the durations of assets and liabilities, resulting in interest rate sensitivity.',
                'mitigating_actions' => 'Monitor interest rate movements and assess the organizations exposure to interest rate risk on its assets and liabilities.
                                            Consider interest rate hedging instruments, such as interest rate swaps or caps, to manage and mitigate interest rate risk.
                                            Maintain an appropriate balance between fixed-rate and floating-rate assets and liabilities to minimize interest rate mismatch.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Credit rating risk',
                'risk_desp' => 'The risk of a downgrade in the organizations credit rating, which can lead to higher borrowing costs or restricted access to capital markets.',
                'risk_driver' => 'Credit rating agencies: Assessments and opinions of credit rating agencies on the organizations creditworthiness.
                                     Financial performance and leverage: Factors influencing the organizations financial health, leverage ratios, and debt levels.
                                     Investor perception: Market perception and investor confidence in the organizations creditworthiness.',
                'mitigating_actions' => 'Maintain strong financial performance, cash flow generation, and manageable levels of leverage to improve creditworthiness and ratings.
                                            Develop open and transparent communication channels with credit rating agencies to provide accurate and timely information.
                                            Continuously monitor and manage key credit rating drivers and address any concerns or weaknesses promptly.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Counterparty risk',
                'risk_desp' => ' The risk of financial losses due to the failure of third parties, such as suppliers, customers, or financial institutions, to fulfill their obligations.',
                'risk_driver' => 'Counterparty assessment: Evaluation of the creditworthiness, financial stability, and reputation of counterparties.
                                     Contractual agreements: Quality of contracts, agreements, and risk mitigation provisions with counterparties.
                                     Monitoring and early warning systems: Systems to identify early signs of counterparty distress or non-performance.',
                'mitigating_actions' => 'Establish clear criteria for selecting counterparties and regularly review their creditworthiness and financial stability.
                                            Use legally binding agreements, contracts, and risk mitigation provisions, such as collateral or guarantees, to protect against counterparty risk.
                                            Implement ongoing monitoring of counterparties financial health and set early warning indicators to detect signs of distress.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Strategic risk',
                'risk_desp' => 'The risk of financial losses resulting from poor strategic decisions, inadequate business planning, or failure to adapt to changing market conditions.',
                'risk_driver' => 'Market analysis and forecasting: Accuracy of market analysis, demand forecasting, and identification of emerging trends.
                                     Strategic planning and decision-making: Effectiveness of strategic planning processes, scenario analysis, and risk assessments.
                                     Competitive landscape: Actions and responses of competitors impacting the organizations strategic position.',
                'mitigating_actions' => 'Conduct comprehensive market research and analysis to identify emerging trends, assess competitive landscape, and make informed strategic decisions.
                                            Regularly review and update strategic plans to align with changing market conditions and mitigate strategic risks.
                                            Implement robust risk assessment and scenario analysis to evaluate the potential impact of strategic decisions and identify contingency plans.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Regulatory and compliance risk',
                'risk_desp' => 'The risk of financial losses arising from non-compliance with applicable laws, regulations, and industry standards, which can lead to fines, penalties, or legal action.',
                'risk_driver' => 'Regulatory changes: Timely identification and adaptation to changes in laws, regulations, and reporting requirements.
                                     Compliance management: Effectiveness of compliance frameworks, policies, training, and monitoring systems.
                                     Internal governance: Oversight, accountability, and reporting structures to ensure compliance with regulations.',
                'mitigating_actions' => 'Stay informed about relevant laws, regulations, and reporting requirements and ensure compliance through regular monitoring and updates to internal policies and procedures.
                                            Develop a robust compliance program with clear roles, responsibilities, and training to educate employees on compliance obligations.
                                            Establish strong internal governance structures, including compliance committees and reporting mechanisms, to ensure oversight and accountability.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Reputational risk',
                'risk_desp' => 'The risk of financial losses due to damage to the organizations reputation, resulting in loss of customers, business opportunities, or investor confidence.',
                'risk_driver' => 'Stakeholder perception: Views and opinions of customers, investors, employees, and the general public about the organization.
                                     Brand management: Strategies to build and protect the organizations brand reputation and customer trust.
                                     Crisis management: Prepared',
                'mitigating_actions' => 'Implement effective communication and public relations strategies to build and maintain a positive reputation with stakeholders.
                                            Develop crisis management plans and protocols to respond swiftly and effectively to reputation-threatening events or issues.
                                            Foster a culture of transparency',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'group_name' => 'Financial',
                'risk_name' => 'Financial fraud risk',
                'risk_desp' => 'The risk of financial losses resulting from fraudulent activities, including misappropriation of assets, financial statement manipulation, or insider trading.',
                'risk_driver' => 'Internal controls and segregation of duties: Strength of internal controls, segregation of duties, and fraud prevention measures.
                                     Ethics and integrity: Cultivating a culture of integrity, ethical behavior, and anti-fraud awareness throughout the organization.
                                     Detection and reporting mechanisms: Robust systems and processes to detect, investigate, and report suspected fraud activities.',
                'mitigating_actions' => 'Strengthen internal controls and segregation of duties to prevent and detect fraudulent activities.
                                            Conduct regular fraud risk assessments and implement fraud prevention measures, such as whistleblower hotlines and anonymous reporting channels.
                                            Provide comprehensive fraud awareness training to employees and establish a zero-tolerance policy towards fraudulent behavior.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('risk_group_details')->where('group_name', $value['group_name'])
                ->where('risk_name', $value['risk_name'])
                ->where('risk_desp', $value['risk_desp'])
                ->where('risk_driver', $value['risk_driver'])
                ->where('mitigating_actions', $value['mitigating_actions'])
                ->first();

            if (!$found) {
                DB::table('risk_group_details')->insert([
                    'group_name' => $value['group_name'],
                    'risk_name' => $value['risk_name'],
                    'risk_desp' => $value['risk_desp'],
                    'risk_driver' => $value['risk_driver'],
                    'mitigating_actions' => $value['mitigating_actions'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class DepartmentDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Human Resources',
                'code' => 'HRD',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Finance and Accounting',
                'code' => 'FIN',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Marketing',
                'code' => 'MAR',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Sales',
                'code' => 'SAL',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Operations',
                'code' => 'OPR',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Information Technology',
                'code' => 'ICT',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Research and Development',
                'code' => 'R&D',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Customer Service',
                'code' => 'CUS',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Production',
                'code' => 'PDN',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Quality Assurance',
                'code' => 'QAS',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Supply Chain Management',
                'code' => 'SCM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Legal / Compliance',
                'code' => 'LEG',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Administration',
                'code' => 'ADM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Procurement',
                'code' => 'PRC',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Project Management',
                'code' => 'PMA',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Public Relations',
                'code' => 'PRN',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Communications',
                'code' => 'COM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Training and Development',
                'code' => 'TRD',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Risk Management',
                'code' => 'RIM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Strategic Planning',
                'code' => 'STR',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Business Development',
                'code' => 'BDM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Logistics',
                'code' => 'LOG',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Facilities Management',
                'code' => 'FAM',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Health, Safety and Environmental',
                'code' => 'HSE',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Corporate Social Responsibility',
                'code' => 'CSR',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Internal Audit',
                'code' => 'IAU',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Treasury',
                'code' => 'TRE',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Planning',
                'code' => 'PLN',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Maintenance',
                'code' => 'MNT',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Warehouse',
                'code' => 'WRH',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Quality Control & Inspection',
                'code' => 'QCI',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('department_details')->where('name', $value['name'])->where('code', $value['code'])->first();
            if (!$found) {
                DB::table('department_details')->insert([
                    'name' => $value['name'],
                    'code' => $value['code'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }

        }
    }
}

class ScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $default_values = [
            [
                'name' => 'Workforce management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Employee support',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Admin',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Financial control',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Record keeping',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Promotion',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Market Analysis',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Business Development',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Revenue generation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Customer Acquisition',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Promotion',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Market Analysis',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Business Development',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Operation Realization',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Process Efficiency',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Productivity Enhancement',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Technology support',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'System maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Innovation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Product Advancement',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Customer satisfaction',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Complaint Handling',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Manufacturing',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Product Realization',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Production Planning',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Quality Assurance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Process Management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Quality control',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Product Inspection',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Inventory management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Logistics coordination',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Legal compliance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Regulatory adherence',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Legal Obligation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Administrative support',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Office management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Employee Management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Supplier management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Purchasing',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Sub-contract control',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Project coordination',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Resource allocation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Innovation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Product Advancement',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Project planning',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Reputation management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Media relations',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Internal/external communication',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Messaging',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Skill enhancement',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Employee training',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Training Evaluation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Risk assessment',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Mitigation strategies',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Long-term vision',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Goal setting',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Market Analysis',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Market expansion',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Partnership building',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Transportation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Distribution',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Delivery',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Facility maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Space management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Workplace Health',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Workplace safety',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Environmental compliance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Social impact',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Sustainability',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Internal Audit',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Financial control',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Risk assessment',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Cash management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Financial investments',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Operational planning',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Resource allocation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Equipment maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Repairs',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Electrical Maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Mechanical Maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Instrumentation Maintenance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Inventory storage',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Material Handling',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Management',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Product quality assessment',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Inspection',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('scopes')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('scopes')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}
class SignupLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'limitedto' => '0',
                'name' => '',
                'email' => '',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('signuplimit')->where('limitedto', $value['limitedto'])->where('name', $value['name'])->where('email', $value['email'])->first();
            if (!$found) {
                DB::table('signuplimit')->insert([
                    'limitedto' => $value['limitedto'],
                    'name' => $value['name'],
                    'email' => $value['email'],
                ]);
            }
        }
    }
}

class SuperAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Client Control',
                'email' => 'admin@cgbod.com',
                'password' => '$2y$10$DdYuQOB4dhsbAv/df9LeueXJdqQnupGPvvoOrCnEgy2m3.zNjQJwK',
                'remember_token' => 'ZKmIIdD3xE4gHx2s8mVAp0Ux0qBJRTHcspkuEh0yTkDnwtihQY44jx6AeBpq',
                'token' => 'NULL',
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('super_admins')->where('name', $value['name'])->where('email', $value['email'])
                ->where('password', $value['password'])
                ->where('remember_token', $value['remember_token'])
                ->where('token', $value['token'])
                ->first();

            if (!$found) {
                DB::table('super_admins')->insert([
                    'name' => $value['name'],
                    'email' => $value['email'],
                    'password' => $value['password'],
                    'remember_token' => $value['remember_token'],
                    'token' => $value['token'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }

        }

    }
}

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'name' => 'High',
                'names' => ['High', 'Major NCR'],
                'details' => 'Major Observation',
                'order' => 1,
                'text_color' => '#af1e1e',
                'background_color' => '#f8d4d4',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Medium',
                'names' => ['Medium', 'Moderate', 'Minor NCR'],
                'details' => 'Moderate Observation',
                'order' => 2,
                'text_color' => '#cc8800',
                'background_color' => '#ffebcc',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Low',
                'names' => ['Low', 'OFI'],
                'details' => 'Minor Observation',
                'order' => 3,
                'text_color' => '#176044',
                'background_color' => '#e0edcf',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        $types_count = DB::table('types')->count();

        foreach ($default_values as $value) {
            $found = DB::table('types')->where('name', $value['name'])->first();
            if (!$found && $types_count < 3) {
                DB::table('types')->insert([
                    [
                        'name' => $value['name'],
                        'details' => $value['details'],
                        'text_color' => $value['text_color'],
                        'background_color' => $value['background_color'],
                        'order' => $value['order'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ],
                ]);

            } else {
                DB::table('types')->whereIn('name', $value['names'])->update([
                    'details' => $value['details'],
                    'text_color' => $value['text_color'],
                    'order' => $value['order'],
                    'background_color' => $value['background_color'],
                ]);
            }
        }

    }
}

class CustomerdetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'question' => 'How satisfied are you with the quality of our products?',
                'score' => '10',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were our products delivered on time?',
                'score' => '10',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were our products delivered in good condition?',
                'score' => '10',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How easy was it to place an order with us?',
                'score' => '5',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Did our customer service team meet your expectations?',
                'score' => '5',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How likely are you to recommend our products to others?',
                'score' => '5',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Did our products meet your expectations?',
                'score' => '10',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How satisfied are you with the overall value of our products?',
                'score' => '10',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were there any issues with our products that you would like us to address?',
                'score' => '5',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How satisfied are you with the communication throughout the ordering and delivery process?',
                'score' => '5',
                'type' => 'product',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],

            [
                'question' => 'How satisfied are you with the level of service you received from our team?',
                'score' => '10',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Did our team members communicate clearly and effectively with you?',
                'score' => '5',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How well did our team members understand your needs?',
                'score' => '5',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were our services completed on time?',
                'score' => '10',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were there any issues with our services that you would like us to address?',
                'score' => '5',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How likely are you to recommend our services to others?',
                'score' => '10',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How satisfied are you with the overall value of our services?',
                'score' => '10',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were there any aspects of our services that exceeded your expectations?',
                'score' => '5',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How satisfied are you with the professionalism of our team members?',
                'score' => '5',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How likely are you to use our services again in the future?',
                'score' => '10',
                'type' => 'service',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],

            [
                'question' => 'Were you satisfied with the overall quality of our project work?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Was our team responsive to your questions and concerns during the project?',
                'score' => '5',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were our project timelines and deadlines met to your satisfaction?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were there any unexpected costs or expenses during the project that you were not informed of?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How well did our team communicate with you throughout the project?',
                'score' => '5',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were our team members respectful and professional when working on your property?',
                'score' => '5',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Were there any aspects of the project that did not meet your expectations?',
                'score' => '5',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How satisfied are you with the final result of the project?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'Would you recommend our services to others?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'question' => 'How likely are you to use our services again in the future?',
                'score' => '10',
                'type' => 'project',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];

        foreach ($default_values as $value) {
            $found = DB::table('customer_details')->where('question', $value['question'])
                ->where('score', $value['score'])
                ->where('type', $value['type'])
                ->first();
            if (!$found) {
                DB::table('customer_details')->insert([
                    'question' => $value['question'],
                    'score' => $value['score'],
                    'type' => $value['type'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            }
        }

    }
}
class VendordetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'question' => 'Quality of product in compliance with specification.',
                'score' => '15',
                'type' => 'Product',
                'filled_by' => 'End User',

            ],
            [
                'question' => 'Product Reliability/Durability',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Adequacy of Materials',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Quality and availability of documentation, instructions, technical manuals.',
                'score' => '5',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Prompt and effective correction of situations and conditions',
                'score' => '5',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Adherence to approved delivery schedules',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Customer Focus',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Cooperation and Responsiveness',
                'score' => '5',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Compliance with Laws and Regulations',
                'score' => '5',
                'type' => 'Product',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Health & Safety Performance',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'HSE',
            ],
            [
                'question' => 'Environmental & Energy Performance',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'HSE',
            ],
            [
                'question' => 'Contract/Quote',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Communication',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Delivery',
                'score' => '10',
                'type' => 'Product',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Documentation records, receipts, invoices, and computer generated reports received in a timely manner and in compliance with contract specifications',
                'score' => '5',
                'type' => 'Product',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Overall Performance',
                'score' => '15',
                'type' => 'Product',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Work performed in compliance with contract terms.',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Adequacy and Implementation of Quality Plan',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Adequacy of Materials',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Quality Of workmanship',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Adherence to approved schedules',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Prompt and effective correction of situations and conditions',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Submission of Required Documentation',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Staff professionalism',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Customer Focus',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Cooperation and Responsiveness',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Compliance with Laws and Regulations',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Documentation records, receipts, invoices, and computer generated reports received in a timely manner and in compliance with contract specifications',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Overall Performance',
                'score' => '15',
                'type' => 'Service Provider',
                'filled_by' => 'End User',
            ],
            [
                'question' => 'Health & Safety Performance',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'HSE',
            ],
            [
                'question' => 'Environmental & Energy Performance',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'HSE',
            ],
            [
                'question' => 'House Keeping & HSE Controls',
                'score' => '5',
                'type' => 'Service Provider',
                'filled_by' => 'HSE',
            ],
            [
                'question' => 'Contract management',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'Procurement',
            ],
            [
                'question' => 'Communication',
                'score' => '10',
                'type' => 'Service Provider',
                'filled_by' => 'Procurement',
            ],
        ];
        foreach ($default_values as $item) {
            $found = DB::table('vendordetails')->where('question', $item['question'])->where('score', $item['score'])->where('type', $item['type'])->where('filled_by', $item['filled_by'])->first();
            if (!$found) {
                DB::table('vendordetails')->insert([
                    [
                        'question' => $item['question'],
                        'score' => $item['score'],
                        'type' => $item['type'],
                        'filled_by' => $item['filled_by'],
                    ],
                ]);
            }
        }
    }
}

class VendorresultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'percent' => '0-39',
                'result' => 'Poor',
                'comment' => 'Dismiss/Delete the vendor Find Alternative',
            ],
            [
                'percent' => '40-59',
                'result' => 'Below Average',
                'comment' => 'Send a warning letter',
            ],
            [
                'percent' => '60-74',
                'result' => 'Fair',
                'comment' => 'Communicate for improvement',
            ],
            [
                'percent' => '74-89',
                'result' => 'Good',
                'comment' => 'Can Continue with the vendor',
            ],
            [
                'percent' => '90-100',
                'result' => 'Excellent',
                'comment' => 'Send Appreciation letter',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('vendorresult')->where('percent', $value['percent'])
                ->where('result', $value['result'])
                ->where('comment', $value['comment'])
                ->first();
            if (!$found) {
                DB::table('vendorresult')->insert([
                    'percent' => $value['percent'],
                    'result' => $value['result'],
                    'comment' => $value['comment'],
                ]);
            }
        }

    }
}

class WasteDispoalMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'value' => 'Land fill',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'value' => 'Re- Cycle',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'value' => 'Incineration',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'value' => 'Chemical-physical and biological treatment',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('wastedisposalmethods')->where('value', $value['value'])->first();
            if (!$found) {
                DB::table('wastedisposalmethods')->insert([
                    'value' => $value['value'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}
class WasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'waste_type' => 'Class 1: Dangerous Waste',
                'wastedesc' => '(e.g. flammable,explosive,toxic)',

            ],
            [
                'waste_type' => 'Class 2: Hazardous Waste',
                'wastedesc' => 'Any Material that can causehazards but not dangerous category(e.g. Chemical Waste that cause minor environmental issues)',
            ],
            [
                'waste_type' => 'Class 3: Non Hazardous Waste',
                'wastedesc' => '(e.g. scrap metal,wood,plastic,domestic/kitchen garbage)',
            ],
            [
                'waste_type' => 'Class 4: Inert Waste',
                'wastedesc' => 'Waste that does not undergo any significant physical,biological,chemical change such as glass,concrete,brick',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('wastedesc')->where('waste_type', $value['waste_type'])
                ->where('wastedesc', $value['wastedesc'])->first();
            if (!$found) {
                DB::table('wastedesc')->insert([
                    'waste_type' => $value['waste_type'],
                    'wastedesc' => $value['wastedesc'],
                ]);
            }
        }
    }
}

class HealthCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'First Aid',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Medical Treatment',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Fatality',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('health_categories')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('health_categories')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class HealthTreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Ambulance - Paramedics Called',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Treated at Scene',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Given First Aid',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Transported to Hospital by Ambulance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Went to Hospital on their own',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Diagnostics Performed at Hospital',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Treatment at Hospital',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Medication Prescribed',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Follow-up Recommended',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('health_treatments')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('health_treatments')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
class InjuryWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'BURNS/SCALDS',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'CHEMICAL CONTACTS/SKIN DISORDERS',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'CHEMICAL INGESTION/INHALATION',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'CRUSH INJURIES',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'CUTS/LACERATION/CONTUSION',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'EYE TRAUMA /IRRITATION',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'FRACTURE',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'HEAD TRAUMA/INJURY',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'HEAT RELATED ILLNESS',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'PUNCTURED WOUND',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'SLIPS, TRIPS, FALL',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'SPRAIN,MUSCLE, BACK INJURY',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('injury_workrelatedcases')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('injury_workrelatedcases')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class IllnessNonWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Abdml Pain/Epigastric Pain',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Blood Pressure Check',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Blood Sugar Check',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Chest Pain',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Communicable Disease',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Constipation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Dental/Oral Cases',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Diarrhea',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Ear Condition',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Eye Redness/Irritation',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Flu & Colds Symptoms/Fever',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Genital/Anal Problem',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Loss of Consciousness',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Mild Headache/Migraine',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Mild Skin Rash/Condition',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Muscle/Joint Pain',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Vertigo',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Wound Care (Cleaning & Dressing)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('illness_non_workrelatedcases')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('illness_non_workrelatedcases')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class OpportunityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Context based opportunity',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Hazard based opportunity',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Other opportunity',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('opportunity_types')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('opportunity_types')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class YearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_values = [
            [
                'year' => '2019',
            ],
            [
                'year' => '2020',
            ],
            [
                'year' => '2021',
            ],
            [
                'year' => '2022',
            ],
            [
                'year' => '2023',
            ],
            [
                'year' => '2024',
            ],
            [
                'year' => '2025',
            ],
            [
                'year' => '2026',
            ],
            [
                'year' => '2027',
            ],
            [
                'year' => '2028',
            ],
            [
                'year' => '2029',
            ],
            [
                'year' => '2030',
            ],

        ];
        foreach ($default_values as $value) {
            $found = DB::table('years')->where('year', $value['year'])->first();
            if (!$found) {
                DB::table('years')->insert([
                    'year' => $value['year'],
                ]);
            }
        }

    }
}

class ConformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'stage' => 'OK',
                'classification' => 'Confirm',
                'description' => 'The requirement is fulfilled',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'stage' => 'NC A',
                'classification' => 'Major Nonconformity (Nonconformity A)',
                'description' => 'Nonconformities could be classified as major in the following circumstances:

                                    -> If there are significant doubt that effective process control is in place, or that products or services will meet specified requirements,

                                    -> a number of minor nonconformities associated with the same requirement or issue could demonstrate a systematic failure and thus constitute a major nonconformity.',

                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'stage' => 'NC B',
                'classification' => 'Minor Nonconformity (Nonconformity B)',
                'description' => 'Nonconformities could be classified as minor, if these do not affect the capability of the management system to achieve the intended results',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'stage' => 'PI',
                'classification' => 'Potential for Improvement',
                'description' => 'Items which would allow optimisation of the management system in relation to the requirements of the relevant standard. It is recommended that the company implements these items.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'stage' => 'NA',
                'classification' => 'Not Applicable',
                'description' => 'If the requirement is not Applicable',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('audit_checklist_conformances')->where('stage', $value['stage'])
                ->where('classification', $value['classification'])
                ->where('description', $value['description'])
                ->first();
            if (!$found) {
                DB::table('audit_checklist_conformances')->insert([
                    'stage' => $value['stage'],
                    'classification' => $value['classification'],
                    'description' => $value['description'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class AuditRootCorrectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'title' => 'Lack of communication',
                'root_cause' => 'Poor communication or a lack of effective communication channels that lead to misunderstandings, conflicts, and errors.',
                'corrective_action' => 'Implement regular team meetings or huddles to improve information sharing.
                                                Establish clear communication channels and protocols.
                                                Encourage open dialogue and active listening among team members.
                                                Provide training on effective communication techniques.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Inadequate training',
                'root_cause' => 'Insufficient or improper training that result in employees lacking the necessary skills and knowledge to perform their tasks correctly, leading to mistakes and inefficiencies.',
                'corrective_action' => 'Identify specific skill gaps and provide targeted training programs.
                                                Implement mentorship or buddy systems to facilitate knowledge transfer.
                                                Offer ongoing professional development opportunities.
                                                Conduct regular performance evaluations to identify training needs.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Poor leadership',
                'root_cause' => 'Incompetent or ineffective leadership that create a lack of direction, low morale, and a disorganized work environment.',
                'corrective_action' => 'Provide leadership training and development programs.
                                                Encourage leaders to lead by example and set clear expectations.
                                                Foster open communication channels between leaders and employees.
                                                Implement performance management systems to evaluate and improve leadership effectiveness.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Insufficient resources',
                'root_cause' => 'Insufficient resources, such as funding, manpower, or technology, that hinder productivity, quality, and the ability to meet objectives.',
                'corrective_action' => 'Conduct a thorough resource assessment and allocate resources appropriately.
                                                Seek additional funding or resources through budget adjustments or external sources.
                                                Prioritize resource allocation based on critical needs and strategic objectives.
                                                Explore process improvements and automation to optimize resource utilization.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Unclear goals and objectives',
                'root_cause' => 'Goals and objectives are not well-defined or communicated, that lead to confusion, inefficiency, and a lack of focus.',
                'corrective_action' => 'Clearly define and communicate organizational goals and objectives.
                                                Cascade goals down to individual departments and employees.
                                                Implement a performance management system that aligns individual goals with organizational goals.
                                                Provide regular feedback and updates on progress towards goals.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Inadequate processes or procedures',
                'root_cause' => 'Poorly designed or outdated processes that contribute to errors, delays, and inefficiencies in workflow.',
                'corrective_action' => 'Conduct process audits and identify areas for improvement.
                                                Redesign processes to eliminate bottlenecks, redundancies, and inefficiencies.
                                                Document and communicate standardized procedures and workflows.
                                                Provide training on new processes and ensure ongoing process improvement efforts.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Lack of accountability',
                'root_cause' => 'Individuals or teams are not held accountable for their actions or outcomes, that lead to a lack of responsibility, motivation, and overall performance.',
                'corrective_action' => 'Establish clear roles, responsibilities, and performance expectations.
                                                Implement performance metrics and key performance indicators (KPIs) to measure accountability.
                                                Provide regular feedback and recognize/reward accountable behavior.
                                                Address issues of non-accountability through coaching, corrective actions, or disciplinary measures.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Cultural or organizational issues',
                'root_cause' => 'An unhealthy or toxic work culture, such as a lack of trust, excessive bureaucracy, or resistance to change, that impede productivity and collaboration.',
                'corrective_action' => 'Foster a positive and inclusive work culture through leadership examples and policies.
                                                Encourage employee feedback and suggestions for cultural improvement.
                                                Address conflicts and issues promptly and fairly.
                                                Promote change management strategies to address resistance and drive cultural transformation.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'External factors',
                'root_cause' => 'External factors beyond an organizations control, such as changes in market conditions, economic factors, or legal/regulatory changes, that impact performance and cause issues.',
                'corrective_action' => 'Stay informed about external factors through market research, industry analysis, and regulatory updates.
                                                Develop contingency plans and adapt strategies to mitigate the impact of external factors.
                                                Establish relationships and collaborations with relevant stakeholders to navigate external challenges.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Human error',
                'root_cause' => 'Mistakes, oversights, or negligence by individuals that lead to problems, particularly in situations where human involvement is critical.',
                'corrective_action' => 'Implement training programs on error prevention, quality control, and attention to detail.
                                                Conduct regular performance evaluations and provide constructive feedback.
                                                Foster a culture of learning from mistakes and promoting continuous improvement.
                                                Implement error-proofing measures and process controls where possible.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('audit_root_cause_corrective_action_details')->where('title', $value['title'])
                ->where('root_cause', $value['root_cause'])
                ->where('corrective_action', $value['corrective_action'])->first();
            if (!$found) {
                DB::table('audit_root_cause_corrective_action_details')->insert([
                    'title' => $value['title'],
                    'root_cause' => $value['root_cause'],
                    'corrective_action' => $value['corrective_action'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class CorrectiveRootCauseCorrectiveActionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'title' => 'Lack of Training',
                'root_cause' => 'Employees may not have received adequate training for their tasks.',
                'corrective_action' => 'Implement regular training programs, assess skill gaps, and provide ongoing learning opportunities.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Communication Breakdown',
                'root_cause' => 'Ineffective communication channels or misunderstandings.',
                'corrective_action' => 'Establish clear communication protocols, encourage feedback, and use tools that facilitate transparent communication.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Poor Planning',
                'root_cause' => 'Inadequate or flawed planning processes.',
                'corrective_action' => 'Improve planning methodologies, involve relevant stakeholders, and conduct thorough risk assessments.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Insufficient Resources',
                'root_cause' => 'Not enough resources allocated for a particular task or project.',
                'corrective_action' => 'Conduct resource assessments, reallocate as needed, and ensure proper resource planning for future projects.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Unclear Goals and Objectives',
                'root_cause' => 'Lack of clarity regarding project goals and objectives.',
                'corrective_action' => 'Clearly define and communicate goals, regularly revisit objectives, and ensure alignment across the organization.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Inadequate Quality Control',
                'root_cause' => 'Lack of robust quality control measures.',
                'corrective_action' => 'Implement stringent quality checks, regularly review processes, and invest in quality assurance training.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Procedural Inconsistencies',
                'root_cause' => 'Variability in executing standard procedures.',
                'corrective_action' => 'Standardize procedures, provide detailed guidelines, and conduct regular audits to ensure adherence.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Ineffective Leadership',
                'root_cause' => 'Leadership lacking vision, communication, or strategic direction.',
                'corrective_action' => 'Invest in leadership development, foster a culture of openness, and ensure leaders communicate a clear vision.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Technology Issues',
                'root_cause' => 'Outdated or malfunctioning technology.',
                'corrective_action' => 'Regularly update technology, invest in modern systems, and have contingency plans for technical failures.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Resistance to Change',
                'root_cause' => 'Employees or stakeholders resisting new initiatives.',
                'corrective_action' => 'Implement change management strategies, communicate the benefits of change, and involve stakeholders in the decision-making process.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Inadequate Risk Management',
                'root_cause' => 'Failure to identify and address potential risks.',
                'corrective_action' => 'Develop a robust risk management plan, regularly assess risks, and have contingency plans in place.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Poor Time Management',
                'root_cause' => 'Inefficient use of time resources.',
                'corrective_action' => 'Implement time management training, use productivity tools, and prioritize tasks effectively.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('corrective_root_cause_corrective_action_details')->where('title', $value['title'])
                ->where('root_cause', $value['root_cause'])
                ->where('corrective_action', $value['corrective_action'])->first();
            if (!$found) {
                DB::table('corrective_root_cause_corrective_action_details')->insert([
                    'title' => $value['title'],
                    'root_cause' => $value['root_cause'],
                    'corrective_action' => $value['corrective_action'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class EnvironmentalImpactDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'title' => 'Energy Consumption',
                'impact' => 'Resource Depletion',
                'details' => 'Contributes to carbon footprint and operational costs; reducing energy usage supports sustainability goals.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Water Usage',
                'impact' => 'Resource Depletion',
                'details' => 'Affects local water availability and quality; efficient water management is crucial for environmental conservation.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Waste Generation',
                'impact' => 'Land Contamination',
                'details' => 'Poses environmental hazards; effective waste management minimizes pollution and disposal challenges.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Emissions',
                'impact' => 'Pollution',
                'details' => 'Air, water, and soil pollution; managing emissions is essential for air quality and environmental health.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Raw Material Usage',
                'impact' => 'Resource Depletion',
                'details' => 'Depletion of natural resources; sustainable sourcing promotes environmental conservation.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Air Emission',
                'impact' => 'Air Pollution',
                'details' => 'Respiratory and environmental health issues; controlling air emissions is vital for community well-being.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Noise Pollution',
                'impact' => 'Disrupts Eco Systems',
                'details' => 'Disrupts ecosystems and affects human health; noise reduction measures are essential.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Chemical spillage',
                'impact' => 'Land Contamination',
                'details' => 'Environmental contamination and health risks; proper handling and disposal are critical.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Biodiversity Impact',
                'impact' => 'Disruption of ecosystems',
                'details' => ' Disruption of ecosystems; conservation efforts are necessary to protect biodiversity.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Land Use',
                'impact' => 'Affects ecosystems and habitat',
                'details' => 'Alters ecosystems and habitats; responsible land use minimizes environmental impact.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Greenhouse Gas Emissions',
                'impact' => 'Climate change / Global Warming',
                'details' => 'Contributes to climate change; emission reduction is crucial for global environmental health. The primary greenhouse gases include carbon dioxide (CO2), methane (CH4), nitrous oxide (N2O), fluorinated gases, and water vapor.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => 'Ozone-depleting substances (ODS) Emissions',
                'impact' => 'Ozone- Depletion',
                'details' => 'These substances release chlorine and bromine atoms when they reach the stratosphere, leading to the breakdown of ozone molecules in the ozone layer. The major contributors to ozone layer depletion are chlorofluorocarbons (CFCs), halons, carbon tetrachloride, and methyl chloroform.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('environmental_impact_details')->where('title', $value['title'])
                ->where('impact', $value['impact'])
                ->where('details', $value['details'])->first();
            if (!$found) {
                DB::table('environmental_impact_details')->insert([
                    'title' => $value['title'],
                    'impact' => $value['impact'],
                    'details' => $value['details'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class ManagementAgendaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'ISO 9001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 14001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'ISO 45001',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('management_agenda_types')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('management_agenda_types')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class ManagementAgendaPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'points' => 'Follow-up actions from previous management reviews
                             Changes in external and internal issues that are relevant to the management system
                             Changes in the needs and expectations of interested parties, including compliance obligations
                             Changes in its significant environmental aspects
                             Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity
                             Customer satisfaction and feedback from relevant interested parties,including trends the extent to which quality objectives have been met,including trends
                             Process performance and conformity of products and services,including trends
                             Nonconformities and corrective actions,including Trends
                             Monitoring and measurement results,including trends
                             Audit results,including trends
                             The performance of external providers,including trends
                             Evaluations of compliance with legal & Other requirements
                             Participation& Consultation
                             Communication(s) from external interested parties, including complaints
                             Changes that could affect the Management System. including developments in legal and other requirements
                             Recommendations for improvement',
                'agenda_type' => 'ISO 9001,ISO 14001,ISO 45001',
                'detail_type' => 'type1',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                             Changes in external and internal issues that are relevant to the management system
                             Performance & Effectiveness of Management System
                             Customer satisfaction and feedback from relevant interested parties,including trends
                             The extent to which quality objectives have been met,including trends
                             Process performance and conformity of products and services,including trends
                             Nonconformities and corrective actions,including Trends
                             Monitoring and measurement results,including trends
                             Audit results, Including trends
                             The performance of external providers,including trends
                             Adequacy of resources
                             Changes in Risk & Opportunities.Effectiveness of action taken to address risk & Opportunity
                             Recommendations for improvement',
                'agenda_type' => 'ISO 9001',
                'detail_type' => 'type2',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                            Changes in external and internal issues that are relevant to the management system
                            Changes in the needs and expectations of interested parties, including compliance obligations
                            Changes in its significant environmental aspects
                            Performance & Effectiveness of Management System
                            The extent to which Environmental objectives have been met, including trends
                            Nonconformities and corrective actions, Including Trends
                            Monitoring and measurement results, including trends
                            Fulfillment of its compliance obligation
                            Audit results,including trends
                            Adequacy of resources
                            Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity
                            Relevant communication(s) from interested parties, including complaints;
                            Recommendations for improvement',
                'agenda_type' => 'ISO 14001',
                'detail_type' => 'type3',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                            Changes in external and internal issues that are relevant to the management system
                            Changes in the needs and expectations of interested parties
                            Changes in legal requirements and other requirements
                            Performance & Effectiveness of Management System
                            The extent to which OH&S Policy and OH&S objectives have been met,including trends
                            Incidents, nonconformities and corrective actions, Continual Improvements,including Trends
                            Monitoring and measurement results,including trends
                            Fulfillment of its compliance obligation
                            Audit results, Including trends
                            Consultation and participation of workers
                            OH&S risks and opportunities,trends
                            Adequacy of resources
                            Changes in Risk & Opportunities.Effectiveness of action taken to address risk & Opportunity
                            relevant communication(s) from interested parties, including complaints;
                            Recommendations for improvement',
                'agenda_type' => 'ISO 45001',
                'detail_type' => 'type4',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                            Changes in external and internal issues that are relevant to the management system
                            Changes in the needs and expectations of interested parties, including compliance obligations
                            Changes in its significant environmental aspects
                            Performance & Effectiveness of Management System
                            Customer satisfaction and feedback from relevant interested parties, including trends
                            Process performance and conformity of products and services, including trends
                            The extent to which objectives have been met, including trends
                            Nonconformities and corrective actions, Including Trends
                            Monitoring and measurement results, including trends
                            Fulfillment of its compliance obligation
                            Audit results, Including trends
                            the performance of external providers, including trends
                            Adequacy of resources
                            Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity
                            Relevant communication(s) from interested parties, including complaints
                            Recommendations for improvement',
                'agenda_type' => 'ISO 9001,ISO 14001',
                'detail_type' => 'type5',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                            Changes in external and internal issues that are relevant to the management system
                            Changes in the needs and expectations of interested parties
                            Changes in legal requirements and other requirements
                            Performance & Effectiveness of Management System
                            Customer satisfaction and feedback from relevant interested parties, including trends
                            The extent to which Policy and objectives have been met, including trends
                            Process performance and conformity of products and services, including trends
                            Incidents, nonconformities and corrective actions, Continual Improvements, Including Trends
                            Monitoring and measurement results, including trends
                            Fulfillment of its compliance obligation
                            Audit results, Including trends
                            The performance of external providers, including trends
                            Consultation and participation of workers
                            OH&S risks and opportunities, trends
                            Adequacy of resources
                            Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity
                            relevant communication(s) from interested parties, including complaints
                            Recommendations for improvement',
                'agenda_type' => 'ISO 9001,ISO 45001',
                'detail_type' => 'type6',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'points' => 'Follow-up actions from previous management reviews
                            Changes in external and internal issues that are relevant to the management system
                            Changes in the needs and expectations of interested parties
                            Changes in legal requirements and other requirements
                            Performance & Effectiveness of Management System
                            The extent to which Policy and objectives have been met, including trends
                            Incidents, nonconformities and corrective actions, Continual Improvements, Including Trends
                            Monitoring and measurement results, including trends
                            Fulfillment of its compliance obligation
                            Audit results, Including trends
                            Consultation and participation of workers
                            OH&S risks and opportunities, trends
                            Changes in its significant environmental aspects
                            Adequacy of resources
                            Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity
                            Relevant communication(s) from interested parties, including complaints
                            Recommendations for improvement',
                'agenda_type' => 'ISO 14001,ISO 45001',
                'detail_type' => 'type7',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('management_agenda_points')->where('points', $value['points'])
                ->where('agenda_type', $value['agenda_type'])
                ->where('detail_type', $value['detail_type'])
                ->first();
            if (!$found) {
                DB::table('management_agenda_points')->insert([
                    'points' => $value['points'],
                    'agenda_type' => $value['agenda_type'],
                    'detail_type' => $value['detail_type'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}

class ManagementAgendaPointListSeeder extends Seeder
{
    public function addagendapoints($array)
    {
        return [
            'points' => $array[0],
            'agenda_type' => $array[1],
            'agenda_point_detail_type' => $array[2],
            'created_at' => $array[3],
            'updated_at' => $array[4],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $lists = [
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties, including compliance obligations', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Changes in its significant environmental aspects', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Customer satisfaction and feedback from relevant interested parties,including trends the extent to which quality objectives have been met,including trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Process performance and conformity of products and services,including trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Nonconformities and corrective actions,including Trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results,including trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Audit results,including trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['The performance of external providers,including trends', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Evaluations of compliance with legal & Other requirements', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Participation& Consultation', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Communication(s) from external interested parties, including complaints', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Changes that could affect the Management System. including developments in legal and other requirements', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 9001,ISO 14001,ISO 45001', 'type1', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Customer satisfaction and feedback from relevant interested parties,including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['The extent to which quality objectives have been met,including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Process performance and conformity of products and services,including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Nonconformities and corrective actions,including Trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results,including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Audit results, Including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['The performance of external providers,including trends', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities.Effectiveness of action taken to address risk & Opportunity', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 9001', 'type2', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties, including compliance obligations', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Changes in its significant environmental aspects', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['The extent to which Environmental objectives have been met, including trends', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Nonconformities and corrective actions,including Trends', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results,including trends', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Fulfillment of its compliance obligation', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Audit results,including trends', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities.Effectiveness of action taken to address risk & Opportunity', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Relevant communication(s) from interested parties, including complaints', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 14001', 'type3', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties, including compliance obligations', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Changes in legal requirements and other requirements', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['The extent to which OH&S Policy and OH&S objectives have been met,including trends', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Incidents, nonconformities and corrective actions, Continual Improvements,including Trends', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results,including trends', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Fulfillment of its compliance obligation', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Audit results,including trends', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Consultation and participation of workers', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['OH&S risks and opportunities,trends', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities.Effectiveness of action taken to address risk & Opportunity', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Relevant communication(s) from interested parties, including complaints', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 45001', 'type4', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties, including compliance obligations', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Changes in its significant environmental aspects', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Customer satisfaction and feedback from relevant interested parties, including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Process performance and conformity of products and services, including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['The extent to which objectives have been met, including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Nonconformities and corrective actions, Including Trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results, including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Fulfillment of its compliance obligation', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Audit results, Including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['The performance of external providers, including trends', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Relevant communication(s) from interested parties, including complaints', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 9001,ISO 14001', 'type5', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Changes in legal requirements and other requirements', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Customer satisfaction and feedback from relevant interested parties, including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['The extent to which Policy and objectives have been met, including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Process performance and conformity of products and services, including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Incidents, nonconformities and corrective actions, Continual Improvements, Including Trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results, including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Fulfillment of its compliance obligation', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Audit results, Including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['The performance of external providers, including trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Consultation and participation of workers', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['OH&S risks and opportunities, trends', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Relevant communication(s) from interested parties, including complaints', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 9001,ISO 45001', 'type6', $time, $time]),
            $this->addagendapoints(['Follow-up actions from previous management reviews', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Changes in external and internal issues that are relevant to the management system', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Changes in the needs and expectations of interested parties', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Changes in legal requirements and other requirements', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Performance & Effectiveness of Management System', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['The extent to which Policy and objectives have been met, including trends', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Incidents, nonconformities and corrective actions, Continual Improvements, Including Trends', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Monitoring and measurement results, including trends', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Fulfillment of its compliance obligation', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Audit results, Including trends', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Consultation and participation of workers', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['OH&S risks and opportunities, trends', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Changes in its significant environmental aspects', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Adequacy of resources', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Changes in Risk & Opportunities. Effectiveness of action taken to address risk & Opportunity', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Relevant communication(s) from interested parties, including complaints', 'ISO 14001,ISO 45001', 'type7', $time, $time]),
            $this->addagendapoints(['Recommendations for improvement', 'ISO 14001,ISO 45001', 'type7', $time, $time]),

        ];
        foreach ($lists as $list) {
            $found = DB::table('management_agenda_point_lists')->where('points', $list['points'])
                ->where('agenda_type', $list['agenda_type'])
                ->where('agenda_point_detail_type', $list['agenda_point_detail_type'])
                ->first();
            if (!$found) {
                DB::table('management_agenda_point_lists')->insert([
                    'points' => $list['points'],
                    'agenda_type' => $list['agenda_type'],
                    'agenda_point_detail_type' => $list['agenda_point_detail_type'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

class ManagementReviewLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = ['Meeting room #1', 'Meeting room #2'];
        foreach ($default_values as $item) {
            $found = DB::table('management_locations')->where('name', $item)->first();
            if (!$found) {
                DB::table('management_locations')->insert([
                    [
                        'name' => $item,
                        'created_at' => $time,
                        'updated_at' => $time,
                    ],
                ]);
            }
        }
    }
}
class EmergencyResponseSituationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $default_values = [
            [
                'name' => 'Fire Emergencies',
                'description' => 'These can result from equipment malfunctions, chemical spills, or electrical issues.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Chemical Spills',
                'description' => 'Accidental releases of hazardous materials can lead to environmental and health risks.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Machinery Malfunctions',
                'description' => 'Equipment breakdowns or failures can lead to safety hazards or production disruptions.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Electrical Failures',
                'description' => 'Power outages or electrical malfunctions can halt production and impact safety.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Worker Injuries',
                'description' => 'Accidents involving heavy machinery, falls, or exposure to hazardous materials can occur.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Gas Leaks',
                'description' => 'Leaks of flammable or toxic gases can pose serious health and safety risks.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Structural Collapses',
                'description' => 'Building collapses or structural failures can endanger employees and disrupt operations.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Natural Disasters',
                'description' => 'Earthquakes, floods, hurricanes, and other natural disasters can disrupt operations and cause damage.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Security Threats',
                'description' => 'Unauthorized access, theft, or acts of violence can compromise safety and disrupt operations.',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Fall from height',
                'description' => 'Falling of individual from heights',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('emergency_response_situations')->where('name', $value['name'])
                ->where('description', $value['description'])
                ->first();
            if (!$found) {
                DB::table('emergency_response_situations')->insert([
                    'name' => $value['name'],
                    'description' => $value['description'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }

        }
    }

}
class EmergencyResponseProblemdetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Congestion in walkways',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Alarm not heard',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Employees unsure of what to do/properly',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Staff unsure of responsibilities/response',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Weather-related problems',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Unable to lock doors',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Doors or Exits blocked',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Windows left open',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Doors left open',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Lights left on',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Confusion',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Incident command problems',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Personnel not accounted for/attendance',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Difficulties with evacuation of disabled personnel, or visitors',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Personnel unaccounted for (list # below)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Radio communication problems',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Network /computer problems',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Noise impedes communications',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Personnel not out of sight (lockdown drill)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Long time to evacuate building',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Personnel not serious about drill',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Improper or unavailable supplies',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('emergency_response_problem_details')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('emergency_response_problem_details')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }

}
class EmergencyResponseMitigationdetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'name' => 'Additional training (for personnel or area)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Address need for additional equipment ',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Improved emergency supplies',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Cooperative planning with responders',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Revised emergency procedures',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('emergency_response_mitigation_details')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('emergency_response_mitigation_details')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }

}
class EmailNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $default_values = [
            [
                'module' => 'managementreview',
                'item' => 'Add Management Review',
                'description' => 'while submitting new management review meeting',
                'email_content' => 'New Management review meeting is added to discuss the following agenda. So please prepare for the meeting to present the given agenda',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'managementreview',
                'item' => 'Management Review Meeting Cancelled',
                'description' => 'while cancelling new management review meeting',
                'email_content' => 'We regret to inform you that the meeting scheduled has been cancelled. We apologize for any inconvenience caused. Will inform the rescheduled date as soon as possible.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'customer_satisfaction',
                'item' => 'Customer Satisfaction new survey response',
                'description' => 'while receiveing a new survey response',
                'email_content' => 'We are pleased to inform you that a new survey response has been received in your customer satisfaction module at CGBOD. Please access the survey response in the customer satisfaction module and review the details at your earliest convenience. Client Name Received date Link',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'managementreview',
                'item' => 'Management Review Meeting Change schedule',
                'description' => 'while rescheduling management review meeting',
                'email_content' => 'We regret to inform you that the meeting scheduled has been rescheduled to a different date and time. We apologize for any inconvenience caused. The rescheduled date and time is mentioned below',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'auditmanagement',
                'item' => 'Checklist Removal Request',
                'description' => 'while removing a checklist',
                'email_content' => 'A request for removing checklist has been intialized.Please verify and approve the removal process',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'auditmanagement',
                'item' => 'Checklist Suggest Improvements',
                'description' => 'suggesting some improvements for checklist',
                'email_content' => 'We are pleased to inform you that some improvements have been suggested for this checklist. Please review the suggestions',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'auditmanagement',
                'item' => 'Audit delay notification',
                'description' => 'when scheduled audit is not conducted by the due date',
                'email_content' => 'We noticed that the scheduled audit assigned to you has not been initiated.<br>Please start the audit or reschedule it at your earliest convenience.<br>',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Mitigation action not fully implemented',
                'description' => 'While closure review mitigation action',
                'email_content' => 'We regret to inform you that the mitigation action has not fully implemented. We will update the percent of it to below value.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'New Risk Added',
                'description' => 'While submitting a new risk',
                'email_content' => 'We would like to inform you that a new risk has been added to the Enterprise Risk module at CGBOD. Please review the issue at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Risk Not Approved',
                'description' => 'While the initial review is disapproved by the risk reviewer',
                'email_content' => 'We regret to inform you that your risk has been reviewed and disapproved by the Management Representative in the Enterprise Risk module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'New Opportunity Added',
                'description' => 'While submitting a new opportunity for a risk',
                'email_content' => 'We would like to inform you that a new opportunity has been added for review in the Enterprise Risk module at CGBOD. Please review the opportunity at your earliest convenience in the Enterprise Risk module.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Risk Owner Changed',
                'description' => 'While changing risk owner of the risk',
                'email_content' => 'You are receiving this email because the responsibility (Risk Owner) for the following risk has been changed to you.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Escalate Risk',
                'description' => 'While escalating the risk',
                'email_content' => 'You are receiving this email because a risk has been identified in the Enterprise Risk module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Reminder For Add Mitigation Actions',
                'description' => 'When mitigation action is Missing for a risk',
                'email_content' => 'This is a reminder that no mitigation action has been added for the risk: [Risk Name]. Please click the link below to add at least one mitigation action and prevent further escalation.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Review RACM Records',
                'description' => 'When review request pending',
                'email_content' => "You've been assigned to review the Risk assessment criteria Matrix (RACM). Please complete your review as soon as possible.",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'riskmanagement',
                'item' => 'Approve RACM Records',
                'description' => 'When approval request pending',
                'email_content' => "You've been assigned to Approve the Risk assessment criteria Matrix (RACM). Please complete your Approval as soon as possible.",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Added',
                'description' => 'while submitting new corrective issue',
                'email_content' => 'We would like to inform you that a new corrective issue has been raised in the corrective action module at CGBOD. Please review the issue at your earliest convenience in the corrective action module.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Investigate',
                'description' => 'while submitting new corrective issue',
                'email_content' => 'You are receiving this mail because you are appointed as Team Leader to investigate the rootcause and corrective action for the issue',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Team Members',
                'description' => 'while submitting new corrective issue',
                'email_content' => 'You are receiving this email because you have been appointed as a team member to investigate the rootcause and corrective actions for the issue',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Response Notification',
                'description' => 'while submitting new corrective issue',
                'email_content' => 'You are receiving this email because an issue is assigned to your department in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Awaiting Approval',
                'description' => 'while submitting new corrective issue',
                'email_content' => 'You are receiving this email because an issue is awaiting your approval in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Responsibility Changed',
                'description' => 'while changing corrective issue responsibility',
                'email_content' => 'You are receiving this email because responsibility has been changed to you for an issue in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Review Status Found Not OK',
                'description' => 'while actions rejected by reviewer for corrective issue',
                'email_content' => 'We regret to inform you that the proposed actions were not accepted by the reviewer. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Review Status Found OK',
                'description' => 'while actions accepted by reviewer for corrective issue',
                'email_content' => 'We are pleased to inform you that the proposed actions have been accepted by the reviewer in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Updated',
                'description' => 'while updating exist corrective issue',
                'email_content' => 'You are receiving this email to inform you that the proposed actions have been updated for an issue in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Closed',
                'description' => 'while closing the corrective issue',
                'email_content' => 'We are pleased to inform you that the issue has been closed by the reviewer in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Reopen Issue',
                'description' => 'while reopening the existing corrective issue',
                'email_content' => 'We regret to inform you that an issue has been reopened in the corrective action module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Disapprove',
                'description' => 'while reviewing the corrective issue and rejecting entry by the management representative',
                'email_content' => 'We regret to inform you that an issue has been disapproved by the Management Representative in the corrective action module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Approved',
                'description' => 'while reviewing the corrective issue and approving entry by the management representative',
                'email_content' => 'We are pleased to inform you that the issue has been approved by the Management Representative in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Resubmited',
                'description' => 'while resubmitting the existing corrective issue',
                'email_content' => 'You are receiving this email to inform you that an issue has been resubmitted in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Corrective Issue Resubmit Notification',
                'description' => 'while resubmitting the existing corrective issue',
                'email_content' => 'You are receiving this email to inform you that an issue from your department has been resubmitted in the corrective action module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Escalation Alert: Overdue Action',
                'description' => 'when Correction & Corrective actions are not initiated within the defined target date',
                'email_content' => 'This is to inform you that the following issue / NCR is overdue and has not yet been initiated within the defined target date.<br>Please take immediate action and proceed with the necessary updates.<br>The issue details are as follows:',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Review Request',
                'description' => 'While submitting new objective',
                'email_content' => 'You are receiving this email to inform you that an objective has been submitted for your review in the Objectives module at CGBOD. Please review the objective at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Reviewed',
                'description' => 'While the objective is reviewed by the reviewer',
                'email_content' => 'You are receiving this email to inform you that an objective has been reviewed by the reviewer in the Objectives module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Awaiting Approval',
                'description' => 'While the objective is reviewed by the reviewer',
                'email_content' => 'You are receiving this email because an objective is awaiting your approval in the Objectives module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Approved',
                'description' => 'While the objective is approved by the approver',
                'email_content' => 'You are receiving this email to inform you that an objective has undergone the approval procedure in the Objectives module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Reviewer Changed',
                'description' => 'While changing the reviewer',
                'email_content' => 'You are receiving this email because review responsibility has been changed to you for an objective in the Objectives module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'objectives',
                'item' => 'Objective Approver Changed',
                'description' => 'While changing the approver',
                'email_content' => 'You are receiving this email because approval responsibility has been changed to you for an objective in the Objectives module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Add Email',
                'description' => 'While submitting new compliance',
                'email_content' => 'You are receiving this email because a new legal requirement has been added in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'New Legal Requirement',
                'description' => 'While submitting new compliance',
                'email_content' => 'You are receiving this email because a new legal requirement has been added in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Weekly Review',
                'description' => 'While entry review is rejected',
                'email_content' => 'You are receiving this email to inform you that a leagl requirement is waiting for your review in the Compliance Management module at CGBOD. Please review the compliance at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Revert Compliance',
                'description' => 'While entry review is rejected',
                'email_content' => 'We regret to inform you that a legal requirement has been reverted in the Compliance Management module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Approve Compliance',
                'description' => 'While entry review is approved',
                'email_content' => 'We are pleased to inform you that the legal requirement has been approved by the Management Representative in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Quarterly Review',
                'description' => 'While the legal requirement is awaiting quarter review',
                'email_content' => 'You are receiving this email to inform you that a legal requirement is awaiting quarter review in the Compliance Management module at CGBOD. Please review the legal requirement at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Half Yearly Review',
                'description' => 'While the legal requirement is awaiting half-yearly review',
                'email_content' => 'You are receiving this email to inform you that a legal requirement is awaiting half-yearly review in the Compliance Management module at CGBOD. Please review the legal requirement at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Yearly Review',
                'description' => 'While the legal requirement is awaiting yearly review',
                'email_content' => 'You are receiving this email to inform you that a legal requirement is awaiting yearly review in the Compliance Management module at CGBOD. Please review the legal requirement at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Change Responsibility',
                'description' => 'While changing compliance responsibility',
                'email_content' => 'You are receiving this email because responsibility has been changed to you for a legal requirement in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Change Status',
                'description' => 'While compliance status change',
                'email_content' => 'You are receiving this email to inform you that the compliance status of a legal requirement has been changed in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Legal Requirement Not Fulfilled',
                'description' => 'While the legal requirement is marked as not fulfilled',
                'email_content' => 'We regret to inform you that a legal requirement has been marked as not fulfilled in the Compliance Management module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Near To Expire',
                'description' => 'While the legal requirement is near to expire',
                'email_content' => 'You are receiving this email to inform you that a legal requirement is nearing expiration in the Compliance Management module at CGBOD. Please review the legal requirement at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'License/Permits Added',
                'description' => 'While submitting new license/permits',
                'email_content' => 'You are receiving this email because a license/permits has been added in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'License/Permits Renewal Notification',
                'description' => 'While the license/permits renewal is pending',
                'email_content' => 'You are receiving this email because a license/permits renewal is required in the Compliance Management module at CGBOD. Please renew the license/permits at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'License/Permits Updated',
                'description' => 'While the license/permits updated',
                'email_content' => 'You are receiving this email to inform you that a license/permits has been updated in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'License/Permits Renewed',
                'description' => 'While the license/permits renewed',
                'email_content' => 'You are receiving this email to inform you that a license/permits has been renewed in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Added',
                'description' => 'while submitting new complaint issue',
                'email_content' => 'We would like to inform you that a new complaint issue has been raised in the Customer Complaint module at CGBOD. Please review the issue at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Investigate',
                'description' => 'while submitting new complaint issue',
                'email_content' => 'You are receiving this mail because you are appointed as Team Leader to investigate the complaint for the issue',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Team Members',
                'description' => 'while submitting new complaint issue',
                'email_content' => 'You are receiving this email because you have been appointed as a team member to investigate the complaint for the issue',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Response Notification',
                'description' => 'while submitting new complaint issue',
                'email_content' => 'You are receiving this email because an issue is assigned to your department in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Awaiting Approval',
                'description' => 'while submitting new complaint issue',
                'email_content' => 'You are receiving this email because an issue is awaiting your approval in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Approved',
                'description' => 'While the complaint entry review is approved by the reviewer',
                'email_content' => 'You are receiving this email to inform you that a complaint has been approved by the reviewer in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Disapproved',
                'description' => 'While the complaint entry review is disapproved by the reviewer',
                'email_content' => 'We regret to inform you that a complaint has been disapproved by the reviewer in the Customer Complaint module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Resubmit Notification',
                'description' => 'While resubmitting the complaint',
                'email_content' => 'This email serves to inform you that a complaint has been resubmitted within the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Resubmit',
                'description' => 'While resubmitting the complaint',
                'email_content' => 'We are writing to inform you that a complaint has been resubmitted in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Updated',
                'description' => 'While proposing action for the complaint',
                'email_content' => 'We are writing to inform you that the proposed actions have been updated in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Review Status Found OK',
                'description' => 'While reviewing the action for the complaint',
                'email_content' => 'We are writing to inform you that the proposed actions have been accepted by the reviewer in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Issue Closed',
                'description' => 'While closing the complaint issue',
                'email_content' => 'We are pleased to inform you that the complaint issue has been closed by the reviewer in the Customer Complaint module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Complaint Review Status Found Not OK',
                'description' => 'While more action is required to address the complaint issue',
                'email_content' => 'We regret to inform you that the proposed actions were not accepted by the reviewer. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'complaintmanagement',
                'item' => 'Escalation Alert: Overdue Action',
                'description' => 'Send escalation emails when Correction & Corrective Actions are not initiated within the defined target date',
                'email_content' => 'This is to inform you that the following Customer Complaint is overdue and has not yet been initiated within the defined target date.<br>Please take immediate action and proceed with the necessary updates.<br>The complaint details are as follows:',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'vendormanagement',
                'item' => 'Request evaluation vendor',
                'description' => 'While request evaluation vendor',
                'email_content' => "We’re evaluating our vendor and would appreciate your input.\nPlease complete the evaluation by answering the questions in the form linked below. Your feedback is important to our decision-making process.",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'calibration',
                'item' => 'Change status from Under Calibration to Calibrated',
                'description' => 'Automatic Email Notification for Status Change from "Under Calibration" to "Calibrated"- External',
                'email_content' => "This is to inform you that the status of the following instruments has been updated. Please find the details below:",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'calibration',
                'item' => 'Email Content while instrument status changes',
                'description' => 'Email Content while instrument status changes',
                'email_content' => "This is to inform you that the status of instrument [Instrument No] has been updated from [Previous status] to [New status]. ",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'calibration',
                'item' => 'Email Content for daily notification for Near to expiry & Expired instruments of Internal calibration',
                'description' => 'Email Content for daily notification for Near to expiry & Expired instruments of Internal calibration',
                'email_content' => "This is a reminder that the following Internal calibration instruments are due for calibration. Please review and arrange calibration at your earliest convenience.",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'calibration',
                'item' => 'Email Content for daily notification for Near to expiry & Expired instruments of External calibration',
                'description' => 'Email Content for daily notification for Near to expiry & Expired instruments of External calibration',
                'email_content' => "This is a reminder that the following External calibration instruments are due for calibration. Please review and arrange calibration at your earliest convenience.",
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'correctiveaction',
                'item' => 'Actions Proposed',
                'description' => 'Email notification for new actions proposed',
                'email_content' => 'New actions have been proposed for issue with',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Response Notification',
                'description' => 'while submitting new hse issue',
                'email_content' => 'You are receiving this email because an issue is assigned to your department in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Issue Added',
                'description' => 'while submitting new hse issue',
                'email_content' => 'We would like to inform you that a new hse issue has been raised in the HSE Observations module at CGBOD. Please review the issue at your earliest convenience in the HSE Observations module.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Issue Proposed Actions',
                'description' => 'while submitting propose actions',
                'email_content' => 'We would like to inform you that new actions have been proposed in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Implementation Status',
                'description' => 'When implementation status is updated as 100% by the issue owner',
                'email_content' => 'The implementation status for an issue in the HSE Observations module at CGBOD has been updated to 100% by the issue owner. Please proceed with the closure review at your earliest convenience in the HSE Observations module.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Issue Updated',
                'description' => 'While proposing action for the hse issue',
                'email_content' => 'We are writing to inform you that the proposed actions have been updated in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Review Status Found OK',
                'description' => 'While reviewing the action for the hse issue',
                'email_content' => 'We are writing to inform you that the proposed actions have been accepted by the reviewer in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Review Status Found Not OK',
                'description' => 'While more action is required to address the hse issue',
                'email_content' => 'We regret to inform you that the proposed actions were not accepted by the reviewer. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Responsibility Changed',
                'description' => 'While changing hse issue responsibility',
                'email_content' => 'You are receiving this email because responsibility has been changed to you for an issue in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'hse',
                'item' => 'HSE Issue Closed',
                'description' => 'While closing the hse issue',
                'email_content' => 'We are pleased to inform you that the hse issue has been closed by the reviewer in the HSE Observations module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity On Due Reminder',
                'description' => 'While the opportunity is on due',
                'email_content' => 'You are receiving this email because an opportunity is awaiting for review in the Opportunity module at CGBOD. Please review at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity Approved',
                'description' => 'While the opportunity initial review is approved by the approver',
                'email_content' => 'You are receiving this email to inform you that an opportunity has been approved by the approver in the Opportunity module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity Disapproved',
                'description' => 'While the opportunity initial review is disapproved by the approver',
                'email_content' => 'We regret to inform you that an opportunity has been disapproved by the approver in the Opportunity module at CGBOD. We apologize for any inconvenience caused.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'New Opportunity Added',
                'description' => 'While submitting a new opportunity',
                'email_content' => 'We would like to inform you that a new opportunity has been added for review in the Opportunity module at CGBOD. Please review the opportunity at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity Responsibility Changed',
                'description' => 'While changing responsibility of an opportunity',
                'email_content' => 'You are receiving this email because responsibility has been changed to you for an opportunity in the Opportunity module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity Status',
                'description' => 'When implementation status is updated as 100% by the opportunity owner',
                'email_content' => 'The implementation status for an opportunity in the Opportunity module at CGBOD has been updated to 100% by the opportunity owner. Please proceed with the closure review at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'opportunity',
                'item' => 'Opportunity Closed',
                'description' => 'While closing the opportunity',
                'email_content' => 'We are pleased to inform you that the opportunity has been closed by the reviewer in the Opportunity module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Approval Required: New Regulatory Authority Added',
                'description' => 'While adding a new regulatory authority in the Add Compliance',
                'email_content' => 'You are receiving this email to inform you that a new regulatory authority has been added in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Approval Required: New Legal Document Added',
                'description' => 'While adding a new legal document in the Add Compliance',
                'email_content' => 'You are receiving this email to inform you that a legal document has been added in the Compliance Management module at CGBOD.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Regulatory Authority Approved',
                'description' => 'When the admin approves a new regulatory authority',
                'email_content' => 'Your submitted entry has been approved and is now available in the dropdown for future compliance submissions.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Legal Document Approved',
                'description' => 'When the admin approves a new legal document',
                'email_content' => 'Your submitted entry has been approved and is now available in the dropdown for future compliance submissions.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Regulatory Authority Rejected',
                'description' => 'When the admin rejects a new regulatory authority',
                'email_content' => 'Your submission has been rejected. Please review and resubmit if required.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Legal Document Rejected',
                'description' => 'When the admin rejects a new legal document',
                'email_content' => 'Your submission has been rejected. Please review and resubmit if required.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Request to Review Assigned Compliance - [Compliance ID]',
                'description' => 'While adding a new legal requirement in the Add Compliance',
                'email_content' => 'You have been assigned to review the following compliance. Kindly review it at your earliest convenience.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Reassigned - Request to Review Assigned Compliance - [Compliance ID]',
                'description' => 'When the legal coordinator is changed for a pending review compliance',
                'email_content' => 'The following compliance has been reassigned to you and requires your review:',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'compliance',
                'item' => 'Compliance Reassignment Notification - [Compliance ID]',
                'description' => 'When the legal coordinator is changed for a reviewed compliance',
                'email_content' => 'This is to inform you that the following compliance item has been reassigned to you. It has already been reviewed and is marked as [Current Status].',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'module' => 'nearmiss',
                'item' => 'Initial Review Required',
                'description' => 'When a Near Miss issue is submitted by a guest user',
                'email_content' => 'A new Near Miss has been submitted and is awaiting your initial review.',
                'is_blocked' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],

        ];
        foreach ($default_values as $value) {
            $found = DB::table('email_notifications')->where('module', $value['module'])
                ->where('item', $value['item'])
                ->where('description', $value['description'])
                ->where('email_content', $value['email_content'])
                ->where('is_blocked', $value['is_blocked'])
                ->first();
            if (!$found) {
                DB::table('email_notifications')->insert([
                    'module' => $value['module'],
                    'item' => $value['item'],
                    'description' => $value['description'],
                    'email_content' => $value['email_content'],
                    'is_blocked' => $value['is_blocked'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }

        }

    }
}
class PotentialLossSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $default_values = [
            [
                'losses' => 'Health',
            ],
            [
                'losses' => 'Safety',
            ],
            [
                'losses' => 'Environmental',
            ],
            [
                'losses' => 'Property Damage',
            ],
            [
                'losses' => 'Pandemic',
            ],
            [
                'losses' => 'Business Interruption',
            ],
            [
                'losses' => 'Security',
            ],
            [
                'losses' => 'Social or Community',
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('potential_loss')->where('losses', $value['losses'])->first();
            if (!$found) {
                DB::table('potential_loss')->insert([
                    'losses' => $value['losses'],
                ]);
            }
        }
    }

}

class ObjectiveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");

        $default_values = [
            [
                'name' => 'Operational',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'HSSE',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Learning',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Financial',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Customer',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('objective_types')->where('name', $value['name'])->first();
            if (!$found) {
                DB::table('objective_types')->insert([
                    'name' => $value['name'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

    }
}

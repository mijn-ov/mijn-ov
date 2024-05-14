<?php

namespace App\Http\Controllers;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;
use OpenAI\Factory;

class ChatController extends Controller
{
    public function viewChat()
    {
        return view('app.chat');
    }

    public function viewEmissions()
    {
        return view('app.emissions');
    }

    public function submitMessage()
    {
        // Get the JSON data from the request body
        $json = file_get_contents('php://input');

        // Decode the JSON data
        $data = json_decode($json);

        $client = \OpenAI::factory()
            ->withBaseUri(env('OPENAI_API_BASE') . 'openai/deployments/' . env('ENGINE_NAME'))
            ->withHttpHeader('api-key', env('AZURE_OPENAI_API_KEY'))
            ->withQueryParam('api-version', env('OPENAI_API_VERSION'))
            ->make();

        $prompt = 'Je bent een NS bot helper, het is jouw taak om de juiste data te vinden voor de NS api.

Je krijgt aan het einde van dit bericht een vraag, hierin staat een begin en een eindpunt.
Om de NS api te gebruiken hebben we de afkorting nodig die de NS aan deze twee locaties geeft, het is jouw taak om deze twee te vinden op basis van de lijst onderin de prompt, en in de nieuwe URL te doen, dit is hoe de JSON eruit hoort te zien:
Zorg dat je antwoord altijd dit formaat volgt:
{
"url": "de nieuwe url",
"beschrijving": "reactie als medewerker chatbot",
}

Zet in de beschrijving een reactie op de vraag van de klant, bijvoorbeeld: "hier is uw route van_ naar _! Heb een fijne reis!"

Dit is hoe de standaard URL eruit ziet:
https://gateway.apiportal.ns.nl/reisinformatie-api/api/v3/trips[&fromStation][&toStation][&viaStation][&originWalk][&originBike][&originCar][&destinationWalk][&destinationBike][&destinationCar][&dateTime][&searchForArrival][&departure][&context][&shorterChange][&addChangeTime][&minimalChangeTime][&viaWaitTime][&originAccessible][&travelAssistance][&nsr][&travelAssistanceTransferTime][&accessibilityEquipment1][&accessibilityEquipment2][&searchForAccessibleTrip][&filterTransportMode][&localTrainsOnly][&excludeHighSpeedTrains][&excludeTrainsWithReservationRequired][&product][&discount][&travelClass][&passing][&travelRequestType][&disabledTransportModalities][&firstMileModality][&lastMileModality][&entireTripModality]

Vul straks deze URL alleen de gegevens in die je van de gebruiker krijgt, verwijder de rest uit de URL zodat het een werkende url word.
Vul alleen dingen in de URL als ze kloppen, bijvoorbeeld als iemand vraagt om in 4e klas te reizen kan dat niet.
Als iemand iets vraagd wat niet kan, zet dit dan in de beschrijving.
In het beschrijving vak zet je extra informatie, bijvoorbeeld een error als de naam die ze gaven niet exact klopt met de NS afkorting. Geef anders een korte samenvatting van wat de gebruiker heeft gezegd.

een voorbeeld van wat hier zou kunnen staan is:
"U gaf aan zandpoort, maar het is sandpoort, dit is voor u verbeterd."
Als er geen NS station is binnen 20 minuten loopafstand, zet dan "NULL" in het vak.

Hier is de vraag van de klant:'.
            $data->message

            .'Hier is een lijst met NS afkortingen:
"
Ac Abcoude
Ah Arnhem
Ahp Arnhem Velperpoort
Ahpr Arnhem Presikhaaf
Ahz Arnhem Zuid
Akl Arkel
Akm Akkrum
Alm Almere Centrum
Almb Almere Buiten
Almm Almere Muziekwijk
Almo Almere Oostvaarders
Almp Almere Parkwijk
Amf Amersfoort Centraal
Amfs Amersfoort Schothorst
Aml Almelo
Ampo Almere Poort
Amr Alkmaar
Amri Almelo De Riet
Amrn Alkmaar Noord
Ana Anna Paulowna
Apd Apeldoorn
Apdm Apeldoorn De Maten
Apdo Apeldoorn Osseveld
Apg Appingedam
Apn Alphen aan den Rijn
Arn Arnemuiden
Asa Amsterdam Amstel
Asb Amsterdam Bijlmer ArenA
Asd Amsterdam Centraal
Asdl Amsterdam Lelylaan
Asdm Amsterdam Muiderpoort
Asdz Amsterdam Zuid
Ashd Amsterdam Holendrecht
Asn Assen
Ass Amsterdam Sloterdijk
Assp Amsterdam Science Park
Atn Aalten
Avat Amersfoort Vathorst

B
Bd Breda
Bde Bunde
Bdg Bodegraven
Bdm Bedum
Bdpb Breda-Prinsenbeek
Bet Best
Bf Baflo
Bgn Bergen op Zoom
Bhdv Boven Hardinxveld
Bhv Bilthoven
Bk Beek-Elsloo
Bkf Bovenkarspel Flora
Bkg Bovenkarspel-Grootebroek
Bkl Breukelen
Bl Beilen
Bll Bloemendaal
Bmn Brummen
Bmr Boxmeer
Bn Borne
Bnc Barneveld Centrum
Bnk Bunnik
Bnn Barneveld Noord
Bnz Barneveld Zuid
Bp Buitenpost
Br Blerick
Brd Barendrecht
Brn Baarn
Bsd Beesd
Bsk Boskoop
Bsks Boskoop Snijdelwijk
Bsmz Bussum Zuid
Btl Boxtel
Bv Beverwijk
Bzl Kapelle-Biezelinge

C
Cas Castricum
Ck Cuijk
Cl Culemborg
Co Coevorden
Cps Capelle Schollevaar
Cvm Chevremont

D
Da Daarlerveen
Db Driebergen-Zeist
Ddn Delden
Ddr Dordrecht
Ddrs Dordrecht Stadspolders
Ddzd Dordrecht Zuid
Dei Deinum
Did Didam
Dl Dalfsen
Dld Den Dolder
Dln Dalen
Dmn Diemen
Dmnz Diemen Zuid
Dn Deurne
Dr Dieren
Drh Driehuis
Dron Dronten
Drp Dronrijp
Dt Delft
Dtc Doetinchem
Dtch Doetinchem De Huet
Dtcp Delft Campus
Dv Deventer
Dvc Deventer Colmschate
Dvd Duivendrecht
Dvn Duiven
Dvnk De Vink
Dwe De Westereen
Dz Delfzijl
Dzw Delfzijl West

E
Ec Echt
Ed Ede-Wageningen
Edc Ede Centrum
Edn Eijsden
Eem Eemshaven
Egh Eygelshoven
Eghm Eygelshoven Markt
Ehs Eindhoven Strijp-S
Ehv Eindhoven
Ekz Enkhuizen
Eml Ermelo
Emn Emmen
Emnz Emmen Zuid
Es Enschede
Ese Enschede De Eschmarke
Esk Enschede Kennispark
Est Elst
Etn Etten-Leur

F
Fn Franeker
Fwd Feanwâlden

G
Gbg Gramsbergen
Gbr Glanerbrug
Gd Gouda
Gdg Gouda Goverwelle
Gdm Geldermalsen
Gdr Gaanderen
Gerp Groningen Europapark
Gk Grijpskerk
Gln Geleen Oost
Gn Groningen
Gnd Hardinxveld-Giessendam
Gnn Groningen Noord
Go Goor
Gp Geldrop
Gr Gorinchem
Gs Goes
Gv Den Haag HS
Gvc Den Haag Centraal
Gvm Den Haag Mariahoeve
Gvmw Den Haag Moerwijk
Gw Grou-Jirnsum
Gz Gilze-Rijen


H
Had Heemstede-Aerdenhout
Hb Hoensbroek
Hbzm Hardinxveld Blauwe Zoom
Hd Harderwijk
Hdb Hardenberg
Hde \'t Harde
Hdg Hurdegaryp
Hdr Den Helder
Hdrz Den Helder Zuid
Hfd Hoofddorp
Hgl Hengelo
Hglg Hengelo Gezondheidspark
Hglo Hengelo Oost
Hgv Hoogeveen
Hgz Hoogezand - Sappemeer
Hil Hillegom
Hk Heemskerk
Hks Hoogkarspel
Hlg Harlingen
Hlgh Harlingen Haven
Hlm Haarlem
Hlms Haarlem Spaarnwoude
Hlo Heiloo
Hm Helmond
Hmbh Helmond Brouwhuis
Hmbv Helmond Brandevoort
Hmh Helmond \'t Hout
Hmn Hemmen-Dodewaard
Hn Hoorn
Hnk Hoorn Kersenboogerd
Hno Heino
Hnp Hindeloopen
Hon Holten
Hor Hollandsche Rading
Hr Heerenveen
Hrl Heerlen
Hrlw Heerlen Woonboulevard
Hrn Haren
Hrt Horst-Sevenum
Hry Heerenveen IJsstadion
Ht \'s - Hertogenbosch
Htn Houten
Htnc Houten Castellum
Hto \'s-Hertogenbosch Oost
Hvl Hoevelaken
Hvs Hilversum
Hvsm Hilversum Media Park
Hvsp Hilversum Sportpark
Hwd Heerhugowaard
Hwzb Halfweg-Zwanenburg
Hze Heeze

I
IJt IJlst

K
Kbd Krabbendijke
Kbk Klarenbeek
Klp Veenendaal-De Klomp
Kma Krommenie-Assendelft
Kmr Klimmen-Ransdaal
Kmw Koudum-Molkwerum
Kpn Kampen
Kpnz Kampen Zuid
Krd Kerkrade Centrum
Krg Kruiningen-Yerseke
Ktr Kesteren
Kw Kropswolde
Kz Koog aan de Zaan

L
Laa Den Haag Laan v NOI
Lc Lochem
Ldl Leiden Lammenschans
Ldm Leerdam
Ledn Leiden Centraal
Lg Landgraaf
Lls Lelystad Centrum
Llzm Lansingerland-Zoetermeer
Lp Loppersum
Ltn Lunteren
Ltv Lichtenvoorde-Groenlo
Lut Geleen-Lutterade
Lw Leeuwarden
Lwc Leeuwarden Camminghaburen

M
Mas Maarssen
Mdb Middelburg
Mes Meerssen
Mg Mantgum
Mmlh Mook Molenhoek
Mp Meppel
Mrb Mariënberg
Mrn Maarn
Mt Maastricht
Mth Martenshoek
Mtn Maastricht Noord
Mtr Maastricht Randwyck
Mz Maarheeze

N
Na Nieuw Amsterdam
Ndb Naarden-Bussum
Nh Nuth
Nkk Nijkerk
Nm Nijmegen
Nmd Nijmegen Dukenburg
Nmg Nijmegen Goffert
Nmh Nijmegen Heyendaal
Nml Nijmegen Lent
Ns Nunspeet
Nsch Bad Nieuweschans
Nvd Nijverdal
Nvp Nieuw Vennep
Nwk Nieuwerkerk a/d IJssel

O
O Oss
Obd Obdam
Odb Oudenbosch
Odz Oldenzaal
Omn Ommen
Op Opheusden
Ost Olst
Ot Oisterwijk
Otb Oosterbeek
Ovn Overveen
Ow Oss West

P
Pmo Purmerend Overwhere
Pmr Purmerend
Pmw Purmerend Weidevenne
Pt Putten

R
Rai Amsterdam RAI
Rat Raalte
Rb Rilland-Bath
Rd Roodeschool
Rh Rheden
Rhn Rhenen
Rl Ruurlo
Rlb Rotterdam Lombardijen
Rm Roermond
Rs Rosmalen
Rsd Roosendaal
Rsn Rijssen
Rsw Rijswijk
Rta Rotterdam Alexander
Rtb Rotterdam Blaak
Rtd Rotterdam Centraal
Rtn Rotterdam Noord
Rtst Rotterdam Stadion
Rtz Rotterdam Zuid
Rv Reuver
Rvs Ravenstein

S
Sbk Spaubeek
Sd Soestdijk
Sda Scheemda
Sdm Schiedam Centrum
Sdt Sliedrecht
Sdtb Sliedrecht Baanhoek
Sgl Houthem-Sint Gerlach
Sgn Schagen
Shl Schiphol Airport
Sk Sneek
Sknd Sneek Noord
Sm Swalmen
Sn Schinnen
Sog Schin op Geul
Sptn Santpoort Noord
Sptz Santpoort Zuid
Srn Susteren
Ssh Sassenheim
St Soest
Std Sittard
Stm Stedum
Stv Stavoren
Stz Soest Zuid
Swd Sauwerd
Swk Steenwijk

T
Tb Tilburg
Tbg Terborg
Tbr Tilburg Reeshof
Tbu Tilburg Universiteit
Tg Tegelen
Tl Tiel
Tpsw Tiel Passewaaij
Twl Twello

U
Uhm Uithuizermeeden
Uhz Uithuizen
Ust Usquert
Ut Utrecht Centraal
Utg Uitgeest
Utl Utrecht Lunetten
Utlr Utrecht Leidsche Rijn
Utm Utrecht Maliebaan
Uto Utrecht Overvecht
Utt Utrecht Terwijde
Utvr Utrecht Vaartsche Rijn
Utzl Utrecht Zuilen

V
Vb Voorburg
Vd Vorden
Vdl Voerendaal
Vdm Veendam
Vem Voorst-Empe
Vg Vught
Vh Voorhout
Vhp Vroomshoop
Vk Valkenburg
Vl Venlo
Vlb Vierlingsbeek
Vndc Veenendaal Centrum
Vndw Veenendaal West
Vp Velp
Vry Venray
Vs Vlissingen
Vss Vlissingen Souburg
Vst Voorschoten
Vsv Varsseveld
Vtn Vleuten
Vz Vriezenveen

W
Wad Waddinxveen
Wadn Waddinxveen Noord
Wadt
Wc Wijchen
Wd Woerden
Wdn Wierden
Wf Wolfheze
Wfm Warffum
Wh Wijhe
Wk Workum
Wl Wehl
Wm Wormerveer
Wp Weesp
Ws Winschoten
Wsm Winsum
Wt Weert
Wtv Westervoort
Wv Wolvega
Ww Winterswijk
Www Winterswijk West
Wz Wezep

Y
Ypb Den Haag Ypenburg

Z
Za Zetten-Andelst
Zb Zuidbroek
Zbm Zaltbommel
Zd Zaandam
Zdk Zaandam Kogerveld
Zh Zuidhorn
Zl Zwolle
Zlsh Zwolle Stadshagen
Zlw Lage Zwaluwe
Zp Zutphen
Ztm Zoetermeer
Ztmo Zoetermeer Oost
Zv Zevenaar
Zvb Zevenbergen
Zvt Zandvoort aan Zee
Zwd Zwijndrecht
Zzs Zaandijk Zaanse Schans
"
zorg dat het start en eind veld ALTIJD een ns afkorting zijn, geen andere afkortingen. Als het antwoord niet tussen de gegeven afkorting staat is het altijd fout.';

        $result = $client->chat()->create([
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0,
        ]);


        // Prepare the response
        $response = array(
            'response' => $result->choices[0]->message->content,
        );
        // Encode the response as JSON
        $json_response = json_encode($response);

        // Set the Content-Type header to application/json
        header('Content-Type: application/json');

        // Send the response back to the client
        echo $json_response;
    }
}

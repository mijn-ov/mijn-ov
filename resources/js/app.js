import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('load', init)

let welcomeText;

function init() {
    welcomeText = document.getElementById('welcome-text')
    randomWelcome()
}

function randomWelcome() {
    let currentTime = new Date();

    let hours = currentTime.getHours();
    let minutes = currentTime.getMinutes();

    let formattedTime = hours + ":" + minutes;
    let randstadStations = [
        'Zoetermeer', 'Stompwijk', 'Zoeterwoude', 'Leiden', 'Pijnacker', 'Delft', 'Den Hoorn', 'De Lier', 'Naaldwijk', "'s-Gravenzande",
        'Spijkenisse', 'Geervliet', 'Heenvliet', 'Hellevoetsluis De Kooistee', 'Hellevoetsluis Smitsweg', 'Rockanje', 'Brielle', 'Oostvoorne',
        'Sliedrecht', 'Papendrecht', 'Hendrik-Ido-Ambacht Oostendam', 'Rotterdam Zuidplein', 'Numansdorp A29', 'Oude-Tonge', 'Middelharnis',
        'Dirksland', 'Melissant', 'Stellendam', 'Goedereede', 'Ouddorp', 'Bergschenhoek', 'Lansingerland-Zoetermeer', 'Zoetermeer Station',
        'Zoetermeer Centrum West', 'Rodenrijs Metro', 'Bleiswijk', 'Lansingerland', 'Purmerend Tramplein', 'Ilpendam', 'Watergang', 'Schouw',
        'Diemen', 'Amsterdam Bijlmer ArenA', 'Amsterdam Holendrecht', 'Nieuw-Vennep Getsewoud', 'Hoofddorp Toolenburg', 'Hoofddorp Station',
        'Schiphol', 'Amsterdam Amstelveenseweg', 'Amsterdam Elandsgracht', 'Amsterdam', 'Centraal Station', 'De Kooi', 'Leiderdorp', 'Alrijne Ziekenhuis',
        'Valkenburg', 'Katwijk aan den Rijn', 'Katwijk Gemeentehuis', 'Katwijk Rijnsoever', 'Noordwijk ESTEC', 'Huizen Busstation', 'Huizen Crailo P+R',
        'Naarden Gooimeer', 'Muiden P+R', 'Diemen Diemerknoop', 'Amsterdam Station RAI', 'Amsterdam Station Zuid', 'Hilversum', 'Blaricum P+R',
        "Huizen 't Merk", 'Gooi- en Vechtstreek', 'Haarlem', 'Vijfhuizen', 'Amstelveen', 'Ouderkerk aan de Amstel', 'Amsterdam Gaasperplas',
        'Santpoort', 'Driehuis', 'IJmuiden', 'Heemstede', 'Cruquius', 'Schiphol-Rijk', 'Aalsmeer', 'Uithoorn', 'Badhoevedorp', 'Schiphol Noord',
        'Amsterdam Station Bijlmer ArenA', 'Gouda', 'Stolwijk', 'Bergambacht', 'Schoonhoven', 'Dordrecht', 'Zwijndrecht', 'Rotterdam Kralingse Zoom',
        'Amsterdam Sloterdijk', 'Zaandam De Vlinder', 'Zaandam Zaans Medisch Centrum', 'Amsterdam Westpoort', 'Haarlemmerliede', 'Velsen Zuid',
        'IJmuiden aan Zee', 'Amsterdam Noorderpark', 'Zaandam Kogerveld', 'Zaandam Zaanse Schans', 'Zaandam Hoornseveld', 'Zaandam Station',
        'Aalsmeer Busstation', 'Kudelstaart', 'Alphen aan den Rijn', 'Woubrugge', 'Rijnsaterwoude', 'Leimuiden', 'Almere Station Centrum',
        "Almere 't Oor", 'Almere Hout', 'Alblasserdam', 'Papendrecht Busstation', 'Dordrecht Gezondheidspark', 'Nieuw-Lekkerland', 'Kinderdijk',
        'Ridderkerk', 'Rotterdam Nesselande', 'Rotterdam Alexander', 'Rotterdam Beurs', 'Schiedam Centrum', 'Maassluis', 'Hoek van Holland Strand',
        'Capelle De Terp', 'Capelle Centrum', 'Hoogvliet', 'Spijkenisse De Akkers', 'Amsterdam Isolatorweg', 'Station Lelylaan', 'Duivendrecht',
        'Van der Madeweg', 'Amsterdam Bijlmer ArenA', 'Gein', 'Amsterdam Centraal Station', 'Vijzelgracht', 'Europaplein', 'Noord', 'Gaasperplas',
        'Wateringen', 'Laakkwartier', 'Plaspoelpolder', 'Zeeheldenkwartier', 'Kunstmuseum', 'Statenkwartier', 'Amstelveen Oranjebaan',
        'Amstelveen Stadshart', 'Amstelveen Middenhoven', 'Amstelveen Westwijk', 'Den Haag', 'Kraayenstein', 'Grote Markt', 'Laan van NOI',
        "Voorburg 't Loo", 'Leidschendam Essesteijn', 'Leidsenhage', 'Vrederust', 'Leyweg', 'Zuiderpark', 'Station Hollands Spoor', 'Madurodam',
        'Scheveningen Noorderstrand', 'Haagse Markt', 'Scheveningen Haven/Strand', 'Rijswijk Haagweg', 'P+R Hoornwijck', 'Nootdorp Centrum',
        'Wateringse Veld', 'Moerwijk', 'Amsterdam Zuid', 'Amstelveen Noorderpark', 'Kogerveld', 'Hoorn', 'Edam', 'Oosthuizen', 'Scharwoude',
        'Schiphol-Zuid', 'Amstel', 'Vlaardingen West', 'Amstelstation', 'Diemen Zuid', 'Amsterdam Sloterdijk', 'Buitenweg', 'Amsterdam Noord',
        'Amsterdam P+R Zeeburg', 'Amsterdam Hempoint', 'Ransdorp', 'Zunderdorp', 'Schellingwoude', 'Durgerdam', 'IJburg', 'Diemen Noord',
        'Amsterdam Noord Molenwijk', 'Buikslotermeerplein', 'Noorderhof', 'Oostzanerwerf', 'Dorpskade', 'Amsterdam Station Zuid', 'Amsterdam RAI',
        'Amsterdam Gaasperplas', 'Broek in Waterland', 'Monnickendam', 'Bernhardbrug', 'Katwoude', 'Volendam', 'Edam', 'Amsterdam Isolatorweg',
        'Amsterdam Westpoort', 'Velsen', 'Noorderpark', 'Amstelveen Poortwachter', 'Uithoorn Zijdelwaard', 'Uithoorn Busstation', 'Rotterdam Centraal',
        'Schiebroek', 'Hilligersberg', 'Ommoord', 'Nesselande', 'Honselersdijk', 'Poeldijk', 'Maasland', 'Lijnbaan', 'Naaldwijk', 'Schiedam',
        'Wateringen', 'Rijswijk', 'Vlietland', 'Haven', 'Oranjebaan', 'Aalsmeerderbrug', 'Aalsmeer Van Cleeffkade', 'Aalsmeer Oosteinde', 'Aalsmeer Hortensiaplein',
        'Aalsmeer Dorpsstraat', 'Aalsmeer Galgeweg', 'Amstelveen Busstation', 'Amstelveen Schouw', 'Aalsmeer Molenweg', 'Amstelveen Handelsplein',
        'Amsterdam Bijlmer ArenA', 'Osdorp de Aker', 'Delft Zuid', 'Rotterdam De Esch', 'Rotterdam Alexander', 'Spijkenisse Waterland', 'Schiphol-Centrum',
        'Rotterdam Hofplein', 'Centraal Station', 'Statenweg', 'Zalmplaat', 'Poortugaal', 'Rhoon', 'Rotterdam Kralingse Zoom', 'Ridderkerk Bijdorp',
        'Barendrecht Van der Meerstraat', 'Zuidplein', 'Oude-Tonge', 'Middelharnis', 'Dirksland', 'Melissant', 'Stellendam', 'Goedereede', 'Ouddorp',
        'Bergschenhoek', 'Lansingerland-Zoetermeer', 'Zoetermeer Station', 'Zoetermeer Centrum West', 'Rodenrijs Metro', 'Bleiswijk', 'Lansingerland',
        'Purmerend Tramplein', 'Ilpendam', 'Watergang', 'Schouw', 'Diemen', 'Amsterdam Bijlmer ArenA', 'Amsterdam Holendrecht', 'Nieuw-Vennep Getsewoud',
        'Hoofddorp Toolenburg', 'Hoofddorp Station', 'Schiphol', 'Amsterdam Amstelveenseweg', 'Amsterdam Elandsgracht', 'Amsterdam', 'Centraal Station',
        'De Kooi', 'Leiderdorp', 'Alrijne Ziekenhuis', 'Valkenburg', 'Katwijk aan den Rijn', 'Katwijk Gemeentehuis', 'Katwijk Rijnsoever', 'Noordwijk ESTEC',
        'Huizen Busstation', 'Huizen Crailo P+R', 'Naarden Gooimeer', 'Muiden P+R', 'Diemen Diemerknoop', 'Amsterdam Station RAI', 'Amsterdam Station Zuid',
        'Hilversum', 'Blaricum P+R', "Huizen 't Merk", 'Gooi- en Vechtstreek', 'Haarlem', 'Vijfhuizen', 'Amstelveen', 'Ouderkerk aan de Amstel',
        'Amsterdam Gaasperplas', 'Santpoort', 'Driehuis', 'IJmuiden', 'Heemstede', 'Cruquius', 'Schiphol-Rijk', 'Aalsmeer', 'Uithoorn', 'Badhoevedorp',
        'Schiphol Noord', 'Amsterdam Station Bijlmer ArenA', 'Gouda', 'Stolwijk', 'Bergambacht', 'Schoonhoven', 'Dordrecht', 'Zwijndrecht',
        'Rotterdam Kralingse Zoom', 'Amsterdam Sloterdijk', 'Zaandam De Vlinder', 'Zaandam Zaans Medisch Centrum', 'Amsterdam Westpoort',
        'Haarlemmerliede', 'Velsen Zuid', 'IJmuiden aan Zee', 'Amsterdam Noorderpark', 'Zaandam Kogerveld', 'Zaandam Zaanse Schans', 'Zaandam Hoornseveld',
        'Zaandam Station', 'Aalsmeer Busstation', 'Kudelstaart', 'Alphen aan den Rijn', 'Woubrugge', 'Rijnsaterwoude', 'Leimuiden', 'Almere Station Centrum',
        "Almere 't Oor", 'Almere Hout', 'Alblasserdam', 'Papendrecht Busstation', 'Dordrecht Gezondheidspark', 'Nieuw-Lekkerland', 'Kinderdijk',
        'Ridderkerk', 'Rotterdam Nesselande', 'Rotterdam Alexander', 'Rotterdam Beurs', 'Schiedam Centrum', 'Maassluis', 'Hoek van Holland Strand',
        'Capelle De Terp', 'Capelle Centrum', 'Hoogvliet', 'Spijkenisse De Akkers', 'Amsterdam Isolatorweg', 'Station Lelylaan', 'Duivendrecht',
        'Van der Madeweg', 'Amsterdam Bijlmer ArenA', 'Gein', 'Amsterdam Centraal Station', 'Vijzelgracht', 'Europaplein', 'Noord', 'Gaasperplas',
        'Wateringen', 'Laakkwartier', 'Plaspoelpolder', 'Zeeheldenkwartier', 'Kunstmuseum', 'Statenkwartier', 'Amstelveen Oranjebaan',
        'Amstelveen Stadshart', 'Amstelveen Middenhoven', 'Amstelveen Westwijk', 'Den Haag', 'Kraayenstein', 'Grote Markt', 'Laan van NOI',
        "Voorburg 't Loo", 'Leidschendam Essesteijn', 'Leidsenhage', 'Vrederust', 'Leyweg', 'Zuiderpark', 'Station Hollands Spoor', 'Madurodam',
        'Scheveningen Noorderstrand', 'Haagse Markt', 'Scheveningen Haven/Strand', 'Rijswijk Haagweg', 'P+R Hoornwijck', 'Nootdorp Centrum',
        'Wateringse Veld', 'Moerwijk', 'Amsterdam Zuid', 'Amstelveen Noorderpark', 'Kogerveld', 'Hoorn', 'Edam', 'Oosthuizen', 'Scharwoude',
        'Schiphol-Zuid', 'Amstel', 'Vlaardingen West', 'Amstelstation', 'Diemen Zuid', 'Amsterdam Sloterdijk', 'Buitenweg', 'Amsterdam Noord',
        'Amsterdam P+R Zeeburg', 'Amsterdam Hempoint', 'Ransdorp', 'Zunderdorp', 'Schellingwoude', 'Durgerdam', 'IJburg', 'Diemen Noord',
        'Amsterdam Noord Molenwijk', 'Buikslotermeerplein', 'Noorderhof', 'Oostzanerwerf', 'Dorpskade', 'Amsterdam Station Zuid', 'Amsterdam RAI',
        'Amsterdam Gaasperplas', 'Broek in Waterland', 'Monnickendam', 'Bernhardbrug', 'Katwoude', 'Volendam', 'Edam', 'Amsterdam Isolatorweg',
        'Amsterdam Westpoort', 'Velsen', 'Noorderpark', 'Amstelveen Poortwachter', 'Uithoorn Zijdelwaard', 'Uithoorn Busstation', 'Rotterdam Centraal',
        'Schiebroek', 'Hilligersberg', 'Ommoord', 'Nesselande', 'Honselersdijk', 'Poeldijk', 'Maasland', 'Lijnbaan', 'Naaldwijk', 'Schiedam',
        'Wateringen', 'Rijswijk', 'Vlietland', 'Haven', 'Oranjebaan', 'Aalsmeerderbrug', 'Aalsmeer Van Cleeffkade', 'Aalsmeer Oosteinde', 'Aalsmeer Hortensiaplein',
        'Aalsmeer Dorpsstraat', 'Aalsmeer Galgeweg', 'Amstelveen Busstation', 'Amstelveen Schouw', 'Aalsmeer Molenweg', 'Amstelveen Handelsplein',
        'Amsterdam Bijlmer ArenA', 'Osdorp de Aker', 'Delft Zuid', 'Rotterdam De Esch', 'Rotterdam Alexander', 'Spijkenisse Waterland', 'Schiphol-Centrum',
        'Rotterdam Hofplein', 'Centraal Station', 'Statenweg', 'Zalmplaat', 'Poortugaal', 'Rhoon', 'Rotterdam Kralingse Zoom', 'Ridderkerk Bijdorp',
        'Barendrecht Van der Meerstraat', 'Zuidplein', 'Oude-Tonge', 'Middelharnis', 'Dirksland', 'Melissant', 'Stellendam', 'Goedereede', 'Ouddorp'
    ];


    let welcome = [`“Wat is de snelste route naar ${randstadStations[Math.floor(Math.random() * randstadStations.length)]}?”`, `“Hoe kom ik bij ${randstadStations[Math.floor(Math.random() * randstadStations.length)]}?”`, `“Ik moet om ${formattedTime} in ${randstadStations[Math.floor(Math.random() * randstadStations.length)]} zijn.”`, `“Hoe laat gaat bus ${Math.floor(Math.random() * 300)}?”`, `“Hoe ga ik van ${randstadStations[Math.floor(Math.random() * randstadStations.length)]} naar ${randstadStations[Math.floor(Math.random() * randstadStations.length)]}?”`]

    welcomeText.innerHTML = welcome[Math.floor(Math.random() * welcome.length)]
}

<?php declare(strict_types=1);

namespace SoftRules\PHP\Enums;

enum eStyleType: string
{
    case slider = 'slider';
	case foutmeldingpagina = 'foutmeldingpagina';
	case meldingicoon = 'meldingicoon';
	case formulier = 'formulier';
	case minimal = 'minimal';
	case slotpagina = 'slotpagina';
	case kvkblok = 'kvk-blok'; // should be kvk-blok
	case popup = 'popup';
	case kvk = 'kvk';
	case titel = 'titel';
	case hoofddekkingen = 'hoofddekkingen';
	case hoofddekking = 'hoofddekking';
	case positiefpunt = 'positiefpunt';
	case negatiefpunt = 'negatiefpunt';
	case verkoperhoofddekkingen = 'verkoperhoofddekkingen';
	case dekkingpunten = 'dekkingpunten';
	case postitiefpunt = 'postitiefpunt';
	case meerinfo = 'meerinfo';
	case aanvullendedekkingen = 'aanvullendedekkingen';
	case aanvullendedekking = 'aanvullendedekking';
	case ncbadvies = 'ncbadvies';
	case vrijeclausule = 'vrijeclausule';
	case totalepremie = 'totalepremie';
	case betaaltermijn = 'betaaltermijn';
	case knoppengroep = 'knoppengroep';
	case maatwerkuitleg = 'maatwerkuitleg';
	case totaalpremietitel = 'totaalpremietitel';
	case bedanktpagina = 'bedanktpagina';
	case strikethrough = 'strikethrough';
	case slotopmerkingen = 'slotopmerkingen';
	case tweekeuzes = 'tweekeuzes';
	case driekeuzes = 'driekeuzes';
	case nolabel = 'nolabel';
	case none = '';
}
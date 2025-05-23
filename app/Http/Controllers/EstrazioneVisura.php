<?php
namespace App\Http\Controllers;

use Illuminate\Support\Arr;

class EstrazioneVisura extends Controller
{
    public function index()
    {
        $visura = $this->visura();

        // return response()->json($visura);

        $imma = Arr::get($visura, "DatiImmatricolazioni.0", null);

        $sivi                        = new SIVI();
        $sivi->data_immatricolazione = Arr::get($imma, "DataPrimaImmatricolazione", "ciccio");

        $sivi->provincia_emissione_carta = trim(Arr::get($imma, "Provincia"));
        return response()->json(["sivi" => $sivi]);
    }

    public function visura(): array
    {
        $json_visura = file_get_contents(__DIR__ . "/ff072fa.json");
        return json_decode($json_visura, true);
    }
}

class SIVI
{
    public ?string $data_immatricolazione;        // DataPrimaImmatricolazione, se null allora DataEmissione
    public ?string $provincia_emissione_carta;    // Provincia
    public ?string $tipo_veicolo;                 // CodTipoVeicolo
    public ?string $intestatario_cf;              // CodiceFiscaleIntestatario
    public ?string $intestatario_istat_provincia; // ComuneIstatResidenza (primi 3 char)
    public ?string $intestatario_istat_comune;    // ComuneIstatResidenza
    public ?string $intestatario_provincia;       // ProvinciaResidenza
    public ?string $intestatario_comune;          // ComuneResidenza
    public ?string $alimentazione;                // CodAlimentazione
    public ?string $potenza;                      // PotenzaMassima, togli tutto quello che non è un digit
    public ?string $cilindrata;                   // Cilindrata, togli tutto tranne digit e il punto, e poi trasforma in un int
    public ?string $cavalli_fiscali;              // PotenzaFiscale
    public ?string $peso;                         // PesoPienoCarico
    public ?string $peso_tara;                    // Tara
    public ?string $peso_rimorchiabile;           // PesoMassimoRimorchiabile
    public ?string $marce;                        // NumeroMarce
    public ?string $posti;                        // NumeroPosti
    public ?string $codice_omologazione;          // Omologazione
    public ?string $telaio;                       // Telaio
}

class SITA
{
    public ?string $cf_proprietario;         //
    public ?string $cf_contraente;           //
    public ?string $data_scadenza_copertura; //
    public ?string $data_inizio_copertura;   //
    public ?string $data_scadenza_mora;      //
    public ?string $data_scadenza_contratto; //
    public ?string $codice_impresa;          //
    public ?string $numero_polizza;          //
    public ?string $codice_iur;              //
    public ?string $tipoVeicolo;             //
    public ?string $oraEffettoCopertura;     //
}

class ATRC
{
    public ?string $data_scadenza_contratto;           //
    public ?string $codice_compagnia_provenienza;      //
    public ?string $cu_provenienza;                    //
    public ?string $cu_assegnazione;                   //
    public ?string $cu_recuperata_con_bersani;         //
    public ?string $classe_interna_provenienza;        //
    public ?string $classe_interna_assegnazione;       //
    public ?string $contraente_nome;                   //
    public ?string $contraente_cognome;                //
    public ?string $contraente_cf;                     //
    public ?string $proprietario_nome;                 //
    public ?string $proprietario_cognome;              //
    public ?string $proprietario_cf;                   //
    public ?string $numero_franchige_non_corrisposte;  //
    public ?string $importo_franchige_non_corrisposte; //
    public ?string $numero_polizza;                    //
    public ?string $forma_tariffaria;                  //
    public ?string $codice_iur;                        //
}

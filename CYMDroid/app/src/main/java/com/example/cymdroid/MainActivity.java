package com.example.cymdroid;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager2.widget.ViewPager2;

import android.app.ActionBar;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.telephony.PhoneStateListener;
import android.telephony.TelephonyManager;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextClock;
import android.net.*;
import android.widget.TextView;
import android.widget.Toast;

import java.io.UnsupportedEncodingException;
import java.net.*;
import java.util.HashMap;
import java.util.Map;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.dialog.MaterialAlertDialogBuilder;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;
import com.android.volley.RequestQueue;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    /** Etiquette pour les messages de log */
    private static final String TAG_LOG = "ACCES WEB";
    /** URL du Web service, paramétrée par le titre du film recherché (avec %s)
     * Le Web service permet de récupérer une fiche descriptive du film (si elle
     * existe)
     * clé pour utiliser le Web Service = 89f6b9ef
     */
    private static final String URL_API_KEY = "https://cymyellow1.000webhostapp.com/API/login/%s/%s";
    private static final String URL_CODE_USER = "https://cymyellow1.000webhostapp.com/API/getCodeUser/%s/%s ";

    private String nomUtilisateur;

    private String motDePasseUtilisateur;
    private String apiKey;
    private String codeCompte;
    private RequestQueue fileRequete;
    private TextView test;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        test = findViewById(R.id.test);

//        Toolbar barreOutil = findViewById(R.id.tool_bar);
//        setSupportActionBar(barreOutil);
        getSupportActionBar().setDisplayShowHomeEnabled(true);
        getSupportActionBar().setIcon(R.mipmap.logo_foreground);

        /*
         * on récupère un accès sur le ViewPager défini dans la vue
         * ainsi que sur le TabLayout qui gèrera les onglets
         */
        ViewPager2 gestionnairePagination = findViewById(R.id.activity_main_viewpager);
        TabLayout gestionnaireOnglet = findViewById(R.id.tab_layout);
        /*
         * on associe au ViewPager un adaptateur (c'est lui qui organise le défilement
         * entre les fragments à afficher)
         */
        gestionnairePagination.setAdapter(new AdaptateurPage(this)) ;
        /*
         * On regroupe dans un tableau les intitulés des boutons d'onglet
         */
        String[] titreOnglet = {
                getString(R.string.nav_accueil),
                getString(R.string.nav_historique),
                getString(R.string.nav_humeurs)};
        /*
         * On crée une instance de type TabLayoutMediator qui fera le lien entre
         * le gestionnaire de pagination et le gestionnaire des onglets
         * La méthode onConfigureTab permet de préciser quel initulé de bouton d'onglets
         * correspond à tel ou tel onglet, selon la position de celui-ci
         * L'instance TabLayoutMediator est attachée à l'activité courante
         *
         */
        new TabLayoutMediator(gestionnaireOnglet, gestionnairePagination,
                new TabLayoutMediator.TabConfigurationStrategy() {
                    @Override public void onConfigureTab(TabLayout.Tab tab, int position) {
                        tab.setText(titreOnglet[position]);
                    }
                }).attach();

        // -------------------------------------- WEB SERVICE --------------------------------------

        // on vérifie si la connexion à Internet est possible
        ConnectivityManager gestionnaireConnexion =
                (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo informationReseau = gestionnaireConnexion.getActiveNetworkInfo();
        if (informationReseau == null || ! informationReseau.isConnected()) {
            // problème de connexion réseau
        } else {
            // on crée un écouteur pour les changements de connectivité
            PhoneStateListener ecouteurConnectivite = new PhoneStateListener() {
                @Override
                public void onDataConnectionStateChanged(int etat) {
                    switch(etat) {
                        case TelephonyManager.DATA_CONNECTED :
                            // appareil connecté et réseau disponible.
                            break;
                        case TelephonyManager.DATA_CONNECTING :
                            // appareil en cours de connexion. TODO : Compléter
                            break;
                        case TelephonyManager.DATA_DISCONNECTED :
                            // appareil déconnecté. TODO : Compléter
                            break;
                        case TelephonyManager.DATA_SUSPENDED :
                            // apparail connecté mais transfert de données impossible.
                            // TODO : Compléter
                            break;
                    }
                    super.onDataConnectionStateChanged(etat);
                }
            };
            // on associe l'écouteur au gestionnaire de téléphonie
            TelephonyManager gestionnaireTelephonie =
                    (TelephonyManager) getSystemService(TELEPHONY_SERVICE);
            gestionnaireTelephonie.listen(ecouteurConnectivite,
                    PhoneStateListener.LISTEN_DATA_CONNECTION_STATE);
        }

    }
    public void connecter(View view) {
        // Create an alert builder
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Connexion");

        // set the custom layout
        final View customLayout = getLayoutInflater().inflate(R.layout.alert_connexion,null);
        builder.setView(customLayout);

        // add a button
        builder.setPositiveButton(
                "OK",
        new DialogInterface.OnClickListener() {

            @Override
            public void onClick(
                    DialogInterface dialog,
                    int which)
            {

                // send data from the
                // AlertDialog to the Activity
                EditText utilisateur
                        = customLayout
                        .findViewById(
                                R.id.nom_utilisateur);

                // AlertDialog to the Activity
                EditText motDePasse
                        = customLayout
                        .findViewById(
                                R.id.edit_mdp);
                try {
                    // les valeurs saisies par l'utilisateur sont récupérés et encodé en UTF-8
                    String util = URLEncoder.encode(utilisateur.getText().toString(), "UTF-8");
                    String mdpUtil = URLEncoder.encode(motDePasse.getText().toString(), "UTF-8");
                    sendDialogDataToActivity(util,mdpUtil);
                } catch(UnsupportedEncodingException erreur) {
                }
            }
        });
        // create and show
        // the alert dialog
        AlertDialog dialog
                = builder.create();
        dialog.show();
    }

    // Do something with the data
    // coming from the AlertDialog
    private void sendDialogDataToActivity(String utilisateur, String motDePasse) {
        nomUtilisateur = utilisateur;
        motDePasseUtilisateur = motDePasse;
        getApiKey();
//        getCodeUser();
    }



//    ______________________________WEB SERVICE___________________________________

    /**
     * Renvoie la file d'attente pour les requêtes Web :
     * - si la file n'existe pas encore : elle est créée puis renvoyée
     * - si une file d'attente existe déjà : elle est renvoyée
     * On assure ainsi l'unicité de la file d'attente
     * @return RequestQueue une file d'attente pour les requêtes Volley
     */
    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(this);
        }
        // sinon
        return fileRequete;
    }

    /**
     * Utilisation de la méthode GET pour consulter la liste de tous les types de clients
     * Cette requête nécessite un header contenant les données d'authentification
     * Avec cette version : le résultat de la requête est affiché directement en tant
     * qu'objet Json.
     */
    private void getApiKey() {
        String url = String.format(URL_API_KEY, nomUtilisateur, motDePasseUtilisateur);

        /*
         * on crée une requête GET, paramètrée par l'url préparée ci-dessus,
         * Le résultat de cette requête sera un objet JSon, donc la requête est de type
         * JsonObjectRequest
         */
        JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET, url,
                null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray reponse) {
                        setZoneResultatAvecObjetJson1(reponse);
                    }
                },
                // écouteur du retour de la requête si aucun résultat n'est renvoyé
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError erreur) {
                    }
                });

        // la requête est placée dans la file d'attente des requêtes
        getFileRequete().add(requeteVolley);
    }

    /**
     * Utilisation de la méthode GET pour consulter la liste de tous les types de clients
     * Cette requête nécessite un header contenant les données d'authentification
     * Avec cette version : le résultat de la requête est affiché directement en tant
     * qu'objet Json.
     */
    private void getCodeUser() {
        String url = String.format(URL_CODE_USER, nomUtilisateur, motDePasseUtilisateur);

        /*
         * on crée une requête GET, paramètrée par l'url préparée ci-dessus,
         * Le résultat de cette requête sera un objet JSon, donc la requête est de type
         * JsonObjectRequest
         */
        JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET, url,
                null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray reponse) {
                        setZoneResultatAvecObjetJson(reponse);
                    }
                },
                // écouteur du retour de la requête si aucun résultat n'est renvoyé
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError erreur) {
                    }
                });

        // la requête est placée dans la file d'attente des requêtes
        getFileRequete().add(requeteVolley);
    }

    /**
     * Gestion de la réponse à la requête de recherche de communes à partir d'un
     * code postal
     * Extraction des données de la réponse de la requête afin de les afficher
     * dans le TextView de résultat
     * @param reponse  réponse à la requête, sous la forme d'un JSONArray
     */
    public void setZoneResultatAvecObjetJson(JSONArray reponse) {
        JSONObject objetJson;
        StringBuilder resultatFormate = new StringBuilder();
        try {
            for (int i = 0; i < reponse.length(); i++) {
                try {
                    // on récupère l’objet Json situé en position i dans le tableau
                    objetJson = reponse.getJSONObject(i);
                    resultatFormate.append(objetJson.getString("Code_User"));
                } catch (JSONException erreur) {

                }
            }
            test.setText(resultatFormate.toString());
        } catch (Exception erreur) {
        }
    }

    /**
     * Gestion de la réponse à la requête de recherche de communes à partir d'un
     * code postal
     * Extraction des données de la réponse de la requête afin de les afficher
     * dans le TextView de résultat
     * @param reponse  réponse à la requête, sous la forme d'un JSONArray
     */
    public void setZoneResultatAvecObjetJson1(JSONArray reponse) {
        JSONObject objetJson;
        String resultatFormate="bb";
        try {
//            for (int i = 0; i < reponse.length(); i++) {
                try {
                    // on récupère l’objet Json situé en position i dans le tableau
                    objetJson = reponse.getJSONObject(0);
                    resultatFormate=objetJson.getString("APIKEY");
                } catch (JSONException erreur) {

                }
//            }
            test.setText(resultatFormate);
        } catch (Exception erreur) {
        }
    }
}

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
import com.android.volley.toolbox.JsonObjectRequest;
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
    private static final String URL_CODE_USER = "https://cymyellow1.000webhostapp.com/API/getCodeUser/%s/%s";

    private static final String URL_LAST_HUMEUR = "https://cymyellow1.000webhostapp.com/API/fiveLastHumeurs";

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
        getCodeUser();
        getFiveHumeurs();
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
    public void getApiKey() {
        String url = String.format(URL_API_KEY, nomUtilisateur, motDePasseUtilisateur);

        JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.GET, url,
                null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject reponse) {
                        setZoneResultatAvecObjetJson1(reponse);
                    }
                },
                // écouteur du retour de la requête si aucun résultat n'est renvoyé
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError erreur) {
                        test.setText(erreur.toString());
                    }
                });
        // la requête est placée dans la file d'attente des requêtes
        getFileRequete().add(requeteVolley);

//        StringRequest requeteVolley = new StringRequest(Request.Method.GET, url,
//                // écouteur de la réponse renvoyée par la requête
//                new Response.Listener<String>() {
//                    @Override
//                    public void onResponse(String reponse) {
//                        test.setText("Début de la réponse obtenue"
//                                + reponse.substring(0, Math.min(400, reponse.length())));
//                    }
//                },
//                // écouteur du retour de la requête si aucun résultat n'est renvoyé
//                new Response.ErrorListener() {
//                    @Override
//                    public void onErrorResponse(VolleyError erreur) {
//                        test.setText("erreur");
//                    }
//                });
//        // la requête est placée dans la file d'attente des requêtes
//        getFileRequete().add(requeteVolley);

        /*
         * on crée une requête GET, paramètrée par l'url préparée ci-dessus,
         * Le résultat de cette requête sera un objet JSon, donc la requête est de type
         * JsonObjectRequest
         */
//        JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET, url,
//                null,
//                // écouteur de la réponse renvoyée par la requête
//                new Response.Listener<JSONArray>() {
//                    @Override
//                    public void onResponse(JSONArray reponse) {
//
//                        setZoneResultatAvecObjetJson1(reponse);
//                    }
//                },
//                // écouteur du retour de la requête si aucun résultat n'est renvoyé
//                new Response.ErrorListener() {
//                    @Override
//                    public void onErrorResponse(VolleyError erreur) {
//                        test.setText(erreur.toString());
//                    }
//                });
//
//        // la requête est placée dans la file d'attente des requêtes
//        getFileRequete().add(requeteVolley);
    }

    /**
     * Utilisation de la méthode GET pour consulter la liste de tous les types de clients
     * Cette requête nécessite un header contenant les données d'authentification
     * Avec cette version : le résultat de la requête est affiché directement en tant
     * qu'objet Json.
     */
    private void getCodeUser() {
        String url = String.format(URL_CODE_USER, nomUtilisateur, motDePasseUtilisateur);

        JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.GET, url,
                null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject reponse) {
                        setZoneResultatAvecObjetJson(reponse);
                    }
                },
                // écouteur du retour de la requête si aucun résultat n'est renvoyé
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError erreur) {
                        test.setText(erreur.toString());
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
    public void setZoneResultatAvecObjetJson(JSONObject reponse) {
        try {
            StringBuilder resultatFormate = new StringBuilder();
            /*
             * on extrait de l'objet Json reponse : le titre, l'année, les auteurs
             * On construit la chaine resultatFormate avec des libellés et le chaînes
             * extraites de l'objet Json
             */
            resultatFormate.append(reponse.getString("Code_User"));
            // on affiche la chaîne fomratée
            test.setText(resultatFormate.toString());
            codeCompte = resultatFormate.toString();
        } catch(JSONException erreur) {
            test.setText("joqhboqupehbv");
        }
    }

    /**
     * Gestion de la réponse à la requête de recherche de communes à partir d'un
     * code postal
     * Extraction des données de la réponse de la requête afin de les afficher
     * dans le TextView de résultat
     * @param reponse  réponse à la requête, sous la forme d'un JSONArray
     */
    public void setZoneResultatAvecObjetJson1(JSONObject reponse) {
        try {
            StringBuilder resultatFormate = new StringBuilder();
            /*
             * on extrait de l'objet Json reponse : le titre, l'année, les auteurs
             * On construit la chaine resultatFormate avec des libellés et le chaînes
             * extraites de l'objet Json
             */
            resultatFormate.append(reponse.getString("APIKEY"));
            // on affiche la chaîne fomratée
            test.setText(resultatFormate.toString());
            apiKey = resultatFormate.toString();
//            getFiveHumeurs();
        } catch(JSONException erreur) {
            test.setText("joqhboqupehbv");
        }

//        JSONObject objetTypeClient; // contiendra successivement chacun des objets
// // du tableau
//        StringBuilder resultatFormate = new StringBuilder();
//        try {
//
//            // on parcourt chacun des objets de l'objet reponse
//            for (int i = 0; i < reponse.length(); i++) {
//                // on récupère l'objet de rang i, en tant qu'objet Json
//                objetTypeClient = reponse.getJSONObject(i);
//                // on récupère la valeur du champs TYPE_CLIENT_DESIGNATION
//                resultatFormate.append(objetTypeClient.getString("APIKEY"));
//            }
//            // on affiche la chaîne formatée
//            test.setText(resultatFormate.toString());
//            System.out.println(resultatFormate);
//        } catch(JSONException erreur) {
//            test.setText("joqhboqupehbv");
//        }


//        try {
//            for (int i = 0; i < reponse.length(); i++) {
//                try {
//                    // on récupère l’objet Json situé en position i dans le tableau
//                    objetJson = reponse.getJSONObject(i);
//                    resultatFormate=objetJson.getString("APIKEY");
//                } catch (JSONException erreur) {
//                    resultatFormate = erreur.toString();
//                }
//            }
//            test.setText(resultatFormate);
//        } catch (Exception erreur) {
//        }
    }

    private void getFiveHumeurs() {

        boolean toutOk;
        /*
         * préparation du nouveau client, à ajouter, en tant qu'objet Json
         * Les informations le concernant sont renseignées avec des valeurs par défaut,
         * sauf le nom du magasin qui est celui renseigné par l'utilisateur
         */
        toutOk = true;
        JSONObject objetAEnvoyer = new JSONObject();
        try {
            objetAEnvoyer.put("code_user", codeCompte);
        } catch (JSONException e) {
            // l'exception ne doit pas se produire
            toutOk = false;
        }
        if (toutOk) {
            /*
             * préparation du client modifié, en tant qu'objet Json
             * Les informations le concernant sont renseignées avec des valeurs par
             * défaut,
             * sauf le nom du magasin qui est celui renseigné par l'utilisateur
             */
            JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.PUT,
                    URL_LAST_HUMEUR, objetAEnvoyer,
                    // Ecouteur pour la réception de la réponse de la requête
                    new com.android.volley.Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject reponse) {
                            // la zone de résultat est renseignée avec le résultat
                            test.setText(reponse.toString());
                        }
                    },
                    // Ecouteur en cas d'erreur
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            test.setText("erreur : " + error);
                        }
                    })
                    // on ajoute un header, contenant la clé d'authentification
            {
                @Override
                public Map getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<>();
                    headers.put("HTTP_CYMAPIKEY", apiKey);
                    System.out.println(headers.toString());
                    return headers;
                }
            };
            // ajout de la requête dans la file d'attente Volley
            getFileRequete().add(requeteVolley);
        }


//        JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.GET, URL_LAST_HUMEUR,
//                null,
//                // écouteur de la réponse renvoyée par la requête
//                new Response.Listener<JSONObject>() {
//                    @Override
//                    public void onResponse(JSONObject reponse) {
//                        setZoneResultatAvecObjetJson3(reponse);
//                    }
//                },
//                // écouteur du retour de la requête si aucun résultat n'est renvoyé
//                new Response.ErrorListener() {
//                    @Override
//                    public void onErrorResponse(VolleyError erreur) {
//                        test.setText(erreur.toString());
//                    }
//                })
//
//                // on ajoute un header, contenant la clé d'authentification
//        {
//            @Override
//            public Map getHeaders() throws AuthFailureError {
//                HashMap<String, String> headers = new HashMap<>();
//                headers.put("HTTP_CYMAPIKEY", apiKey);
//                System.out.println(headers.toString());
//                return headers;
//            }
//        };
//        // la requête est placée dans la file d'attente des requêtes
//        getFileRequete().add(requeteVolley);
    }
    /**
     * Affiche dans la zone de résultat, seulement les libellés des types de client
     * @param reponse objet Json contenant sous la forme d'un tableau le résultat de la
     * recherche de tous les types de clients
     */
    public void setZoneResultatAvecObjetJson3(JSONObject reponse){
//        JSONObject objetTypeClient; // contiendra successivement chacun des objets
//        // du tableau
//        try {
//            StringBuilder resultatFormate = new StringBuilder();
//            // on parcourt chacun des objets de l'objet reponse
//            for (int i = 0; i < reponse.length(); i++) {
//                // on récupère l'objet de rang i, en tant qu'objet Json
//                objetTypeClient = reponse.getJSONObject(i);
//                // on récupère la valeur du champs TYPE_CLIENT_DESIGNATION
//                resultatFormate.append(
//                                objetTypeClient.getString("Humeur_Libelle"))
//                        .append(" - ");
//            }
//            // on affiche la chaîne formatée
//            test.setText(resultatFormate.toString());
//        } catch(JSONException erreur) {
////            Toast.makeText(this, R.string.message_non_trouve, Toast.LENGTH_LONG).show();
//        }

        try {
            StringBuilder resultatFormate = new StringBuilder();
            /*
             * on extrait de l'objet Json reponse : le titre, l'année, les auteurs
             * On construit la chaine resultatFormate avec des libellés et le chaînes
             * extraites de l'objet Json
             */
            resultatFormate.append(reponse.getString("Humeur_Libelle"));
            // on affiche la chaîne fomratée
            test.setText(resultatFormate.toString());
        } catch(JSONException erreur) {
            test.setText("joqhboqupehbv");
        }
    }
}

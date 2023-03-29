package com.example.cymdroid;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.HashMap;
import java.util.Map;

public class FragmentAccueil extends Fragment implements View.OnClickListener {
    private static final String URL_API_KEY = "https://cymyellow1.000webhostapp.com/API/login/%s/%s";
    private static final String URL_CODE_USER = "https://cymyellow1.000webhostapp.com/API/getCodeUser/%s/%s";

    private String nomUtilisateur;

    private String motDePasseUtilisateur;
    private String apiKey;
    private String codeCompte;

    private String resultatApiKey;
    private String resultatCodeCompte;
    private RequestQueue fileRequete;
    private TextView test;
    private Button boutonConnexion;
    private EcouteurGeneration activiteQuiMEcoute;

    public FragmentAccueil() {
    }

    /**
     * Cette méthode est une "factory" : son rôle est de créer une nouvelle instance
     * du fragment de type FragmentAccueil
     * @return une nouvelle instance du fragment FragmentAccueil.
     */
    public static FragmentAccueil newInstance() {
        FragmentAccueil fragment = new FragmentAccueil();
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // On récupère la vue (le layout) associée au fragment accueil et on la renvoie
        View vue = inflater.inflate(R.layout.fragment_accueil, container, false);
        vue.findViewById(R.id.bouton_connexion).setOnClickListener(this);
        test = vue.findViewById(R.id.test);
        return vue;
    }

    @Override
    public void onAttach(Context contexte) {
        super.onAttach(contexte);
        // contexte est l'activité parente du fragment, donc l'activité principale
        activiteQuiMEcoute = (EcouteurGeneration) contexte;
    }

    public void connecter() {
        // Create an alert builder
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
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
                            nomUtilisateur = util;
                            motDePasseUtilisateur = mdpUtil;
                            getApiKey();
//                            sendDialogDataToActivity(util,mdpUtil);
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
//        getFiveHumeurs();
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
            fileRequete = Volley.newRequestQueue(getActivity());
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
//                        System.out.println("codeCompteGetApiKey : " + codeCompte);
                        resultatApiKey= apiKey;
                        getCodeUser();
                        System.out.println("apikeyGetApiKey2 : " + apiKey);
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
                        resultatCodeCompte = codeCompte;
                        System.out.println("CODE: " + resultatCodeCompte);
                        System.out.println("API : " + resultatApiKey);
                        activiteQuiMEcoute.recevoirCodeCompteEtApiKey(resultatCodeCompte,resultatApiKey);
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
//            System.out.println("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa : " + resultatFormate);
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
//            System.out.println("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa : " + resultatFormate);
            // on affiche la chaîne fomratée
            test.setText(resultatFormate.toString());
            apiKey = resultatFormate.toString();
//            System.out.println("codeCompteGetApiKey : " + codeCompte);
            System.out.println("apikeyGetApiKey : " + apiKey);
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

//    private void getFiveHumeurs() {
//
//        boolean toutOk;
//        /*
//         * préparation du nouveau client, à ajouter, en tant qu'objet Json
//         * Les informations le concernant sont renseignées avec des valeurs par défaut,
//         * sauf le nom du magasin qui est celui renseigné par l'utilisateur
//         */
//        toutOk = true;
//        JSONObject objetAEnvoyer = new JSONObject();
//        try {
//            objetAEnvoyer.put("code_user", codeCompte);
//        } catch (JSONException e) {
//            // l'exception ne doit pas se produire
//            toutOk = false;
//        }
//        if (toutOk) {
//            /*
//             * préparation du client modifié, en tant qu'objet Json
//             * Les informations le concernant sont renseignées avec des valeurs par
//             * défaut,
//             * sauf le nom du magasin qui est celui renseigné par l'utilisateur
//             */
//            JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.PUT,
//                    URL_LAST_HUMEUR, objetAEnvoyer,
//                    // Ecouteur pour la réception de la réponse de la requête
//                    new com.android.volley.Response.Listener<JSONObject>() {
//                        @Override
//                        public void onResponse(JSONObject reponse) {
//                            // la zone de résultat est renseignée avec le résultat
//                            test.setText(reponse.toString());
//                        }
//                    },
//                    // Ecouteur en cas d'erreur
//                    new com.android.volley.Response.ErrorListener() {
//                        @Override
//                        public void onErrorResponse(VolleyError error) {
//                            test.setText("erreur : " + error);
//                        }
//                    })
//                    // on ajoute un header, contenant la clé d'authentification
//            {
//                @Override
//                public Map getHeaders() throws AuthFailureError {
//                    HashMap<String, String> headers = new HashMap<>();
//                    headers.put("HTTP_CYMAPIKEY", apiKey);
//                    System.out.println(headers.toString());
//                    return headers;
//                }
//            };
//            // ajout de la requête dans la file d'attente Volley
//            getFileRequete().add(requeteVolley);
//        }


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
//    }
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

    @Override
    public void onClick(View view) {
        connecter();
//        System.out.println("codeCompteA : " + codeCompte);
//        System.out.println("apikeyA : " + apiKey);
//        activiteQuiMEcoute.recevoirCodeCompteEtApiKey(resultatCodeCompte,resultatApiKey);
        System.out.println("FINCOmPTE : " + resultatCodeCompte);
        System.out.println("FINAPI : " + resultatApiKey);
    }

    public interface EcouteurGeneration {
        void recevoirCodeCompteEtApiKey(String codeCompte, String apiKey);
    }
}

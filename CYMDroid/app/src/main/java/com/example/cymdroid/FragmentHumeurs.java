package com.example.cymdroid;

import android.app.Activity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextClock;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.nio.charset.Charset;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public class FragmentHumeurs extends Fragment  implements View.OnClickListener {
    private static final String URL_ADD_HUMEUR = "https://cymyellow1.000webhostapp.com/API/addHumeur/%s";
    private TextClock heure;

    private String[] lesHumeurs;
    private String[] lesEmojis;

    private RequestQueue fileRequete;
    private String choixHumeur;
    private String emojisHumeur;

    private String codeCompteUtil;
    private String apikeyUtil;
    private EditText descriptionHumeur;
    public FragmentHumeurs() {

    }

    /**
     * Cette méthode est une "factory" : son rôle est de créer une nouvelle instance
     * du fragment de type FragmentHumeurs
     * @return une nouvelle instance du fragment FragmentHumeurs.
     */
    public static FragmentHumeurs newInstance() {
        FragmentHumeurs fragment = new FragmentHumeurs();
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
        View vueDuFragment = inflater.inflate(R.layout.fragment_humeurs, container, false);
        heure = vueDuFragment.findViewById(R.id.heure);
        heure.setFormat12Hour("kk:mm:ss");

        descriptionHumeur = vueDuFragment.findViewById(R.id.descriptionHumeur);

        vueDuFragment.findViewById(R.id.btn_ajout).setOnClickListener(this);

        Spinner listeHumeurs = (Spinner) vueDuFragment.findViewById(R.id.spinner);

        lesHumeurs = getResources().getStringArray(R.array.humeurs);
        lesEmojis = getResources().getStringArray(R.array.emojis_humeurs);

        ArrayAdapter<String> adaptateur = new ArrayAdapter<String>(getActivity(), android.R.layout.simple_spinner_item, lesHumeurs);
        listeHumeurs.setAdapter(adaptateur);
        // on associe un écouteur à la liste déroulante
        listeHumeurs.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                choixHumeur = lesHumeurs[position];
                emojisHumeur = lesEmojis[position];
                System.out.println("Humeur choisie : " + choixHumeur);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        // TODO tester si la connexion est reussi
//        vueDuFragment.setVisibility(View.INVISIBLE);

//        String codeCompte;
//        String apikey;
        codeCompteUtil = ((MainActivity) getActivity()).getCodeCompte();
        apikeyUtil = ((MainActivity) getActivity()).getApikey();
        if (codeCompteUtil != null && apikeyUtil != null) {
            System.out.println("Humeurs -----------> codeCompte : " + codeCompteUtil);
            System.out.println("Humeurs -----------> apikey : " + apikeyUtil);
            vueDuFragment.setVisibility(View.VISIBLE);
        } else {
            System.out.println("Humeurs -----------> codeCompte : " + codeCompteUtil);
            System.out.println("Humeurs -----------> apikey : " + apikeyUtil);
            vueDuFragment.setVisibility(View.INVISIBLE);
        }

        return vueDuFragment;
    }
    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(getActivity());
        }
        // sinon
        return fileRequete;
    }

    /**
     * Utilisation de la méthode POST pour ajouter un nouveau client
     * Cette requête nécessite un header contenant les données d'authentification
     * ainsi qu'un body contenant les informations relatives au nouveau client
     * Pour cet exemple, seul le nom du magasin est personnalisé. Les autres informations
     * du client sont renseignées avec une valeur par défaut
     */
    private void postAjoutHumeur(String nomHumeur, String description, String emoji) {
        System.out.println("Humeurs -----------> codeCompte : " + codeCompteUtil);
        System.out.println("Humeurs -----------> apikey : " + apikeyUtil);
        System.out.println("Humeurs -----------> nom : " + nomHumeur);
        System.out.println("Humeurs -----------> descr : " + description);

        String url = String.format(URL_ADD_HUMEUR, codeCompteUtil);

        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.FRANCE);

        String date = dateFormat.format(Calendar.getInstance().getTime());

//        System.out.println(date);

//        final String DEFAUT = "A definir";
        boolean toutOk;
        /*
         * préparation du nouveau client, à ajouter, en tant qu'objet Json
         * Les informations le concernant sont renseignées avec des valeurs par défaut,
         * sauf le nom du magasin qui est celui renseigné par l'utilisateur
         */
        toutOk = true;
        JSONObject objetAEnvoyer = new JSONObject();
        try {
            objetAEnvoyer.put("libelle", nomHumeur);
            objetAEnvoyer.put("emoji", emoji);
            objetAEnvoyer.put("time", date);
            objetAEnvoyer.put("description", description);
            objetAEnvoyer.put("timeConst", date);
        } catch (JSONException e) {
            // l'exception ne doit pas se produire
            toutOk = false;
        }
        if (toutOk) {
            System.out.println("Humeurs -----------> JSON : " + objetAEnvoyer);
            /*
             * Préparation de la requête Volley. La réponse attendue est de type
             * JsonObject
             * REMARQUE : bien noter la présence du 3ème argument du constructeur qui est
             * l'objet Json à transmettre avec la méthode POST, en fait le body de la
             * requête
             */
            JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.PUT,
                    url, objetAEnvoyer,
                    // Ecouteur pour la réception de la réponse de la requête
                    new com.android.volley.Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject reponse) {
                            // la zone de résultat est renseignée avec le résultat
                            // de la requête
//                            <zoneResultat>.setText(reponse.toString())
                            System.out.println(reponse.toString());
                        }
                    },
                    // Ecouteur en cas d'erreur
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
//                            zoneResultat.setText(R.string.message_erreur);
                            System.out.println("Humeurs -----------> Erreur requête : " + error);
                            if (error == null || error.networkResponse == null) {
                                return;
                            }
                            String body;
                            //get status code here
                            String statusCode = java.lang.String.valueOf(error.networkResponse.statusCode);
                            //get response body and parse with appropriate encoding
                            Charset UTF_8 = Charset.forName("UTF-8");
                            body = new String(error.networkResponse.data, UTF_8);

                            System.out.println("body : " + body + " statut : " + statusCode);
                        }
                    })
                    // on ajoute un header, contenant la clé d'authentification
            {
                @Override
                public Map getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<>();
                    headers.put("CYMAPIKEY", apikeyUtil);
                    System.out.println(headers.toString());
                    return headers;
                }
            };
            // ajout de la requête dans la file d'attente Volley
            getFileRequete().add(requeteVolley);
        }
    }

    public void recevoirCodeEtApikey(String codeCompte, String apikey) {
        if (getView() != null) {
            getView().setVisibility(View.VISIBLE);
        }
        codeCompteUtil = codeCompte;
        apikeyUtil = apikey;
//        zoneAleatoire.setText(getString(R.string.message_communication) + nombre);
//        getFiveHumeurs(codeCompte,apikey);
        System.out.println("code : "+ codeCompte + " " + "Apikey : " + apikey);
    }

    @Override
    public void onClick(View view) {
        if (view.getId() == R.id.btn_ajout) {
            if (choixHumeur != null) {
                String choixDescription;
                choixDescription = descriptionHumeur.getText().toString();
                postAjoutHumeur(choixHumeur, choixDescription, emojisHumeur);
            }
        }
    }
}

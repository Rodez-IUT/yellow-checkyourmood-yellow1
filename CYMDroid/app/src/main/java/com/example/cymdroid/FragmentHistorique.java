package com.example.cymdroid;

import static java.util.Arrays.asList;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

public class FragmentHistorique extends Fragment implements AdapterView.OnItemClickListener {

    private static final String URL_LAST_HUMEUR = "https://cymyellow1.000webhostapp.com/API/fiveLastHumeurs";
    private ListView listeHumeurs;
    private String emojis[];

    private String humeurs[];

    private String dates[];
    private TextView aaa;
    private RequestQueue fileRequete;

    public FragmentHistorique() {
    }

    /**
     * Cette méthode est une "factory" : son rôle est de créer une nouvelle instance
     * du fragment de type FragmentHistorique
     * @return une nouvelle instance du fragment FragmentHistorique.
     */
    public static FragmentHistorique newInstance() {
        FragmentHistorique fragment = new FragmentHistorique();
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
        View vueDuFragment = inflater.inflate(R.layout.fragment_historique, container, false);

//        listeHumeurs = vueDuFragment.findViewById(R.id.listeHumeurs);
//        String[] humeurs = new String[]{"Mark","Jane","Sussy","Jan"};
//        ArrayAdapter<String> adaptateur = new ArrayAdapter<String>(getActivity(),
//                android.R.layout.simple_list_item_1, android.R.id.text1,humeurs);
//        listeHumeurs.setAdapter(adaptateur);
//        listeHumeurs.setOnItemClickListener(this);

        listeHumeurs = (ListView) vueDuFragment.findViewById(R.id.listeHumeurs);

        //Création de la ArrayList qui nous permettra de remplir la listView
        ArrayList<HashMap<String, String>> listItem = new ArrayList<HashMap<String, String>>();

        //On déclare la HashMap qui contiendra les informations pour un item
        HashMap<String, String> map;

        //Création d'une HashMap pour insérer les informations du premier item de notre listView
        map = new HashMap<String, String>();
        //on insère un élément emoji que l'on récupérera dans le textView emoji créé dans le fichier row_item.xml
        map.put("emoji", "test");
        //on insère un élément humeur que l'on récupérera dans le textView humeur créé dans le fichier row_item.xml
        map.put("humeur", "test");
        //on insère un élément date que l'on récupérera dans le textView date créé dans le fichier row_item.xml
        map.put("date", "test");
        //on ajoute cette hashMap dans la arrayList
        listItem.add(map);

        map = new HashMap<String, String>();
        map.put("emoji", "test");
        map.put("humeur", "test");
        map.put("date", "test");
        listItem.add(map);

        map = new HashMap<String, String>();
        map.put("emoji", "test");
        map.put("humeur", "test");
        map.put("date", "test");
        listItem.add(map);

        map = new HashMap<String, String>();
        map.put("emoji", "test");
        map.put("humeur", "test");
        map.put("date", "test");
        listItem.add(map);

        //Création d'un SimpleAdapter qui se chargera de mettre les items présents dans notre list (listItem) dans la vue row_item
        SimpleAdapter adaptateur = new SimpleAdapter (getActivity(), listItem, R.layout.row_item,
                new String[] {"emoji", "humeur", "date"}, new int[] {R.id.emoji, R.id.humeur, R.id.date});

        //On attribue à notre listView l'adapter que l'on vient de créer
        listeHumeurs.setAdapter(adaptateur);

        // TODO tester si la connexion est reussi
//        vueDuFragment.setVisibility(View.INVISIBLE);

//        View vueDuFragment;
        String codeCompte;
        String apikey;// nombre à afficher (c'est l'activité principale qui
        // va fournir ce nombre)
        // On récupère la vue associée au fragment deux, et le widget qui affichera le nombre
//        vueDuFragment = inflater.inflate(R.layout.fragment_historique, container, false);
//        zoneAleatoire = vueDuFragment.findViewById(R.id.alea_communique);
        /*
         * on accède à l'activité parente du fragment, avec l'appel à getActivity
         * Puis on invoque le getter de cette activité, pour récupérer le nombre aléatoire
         * actuellement géré par l'activité
         */
        codeCompte = ((MainActivity) getActivity()).getCodeCompte();
        apikey = ((MainActivity) getActivity()).getApikey();
        System.out.println("codeCompte : " + codeCompte);
        System.out.println("apikey : " + apikey);
        /*
         * Dans le cas où aucun nombre aléatoire n'a été généré (ie l'utilisateur n'a pas encore
         * cliqué sur "Générer") , le nombre communiqué par l'activité principale est égal à -1.
         * Si tel est le cas, il ne faut pas l'afficher. +9
         */
        if (codeCompte != null && apikey != null) {
//            vueDuFragment.setVisibility(View.VISIBLE);
            recupererHumeurs(codeCompte,apikey);
        }
        aaa = vueDuFragment.findViewById(R.id.aaa);
        return vueDuFragment;
//        return vueDuFragment;
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {

    }

    private void getFiveHumeurs(String codeCompte, String apiKey) {
        System.out.println("code = " + codeCompte);
        System.out.println("API = " + apiKey);
        boolean toutOk;
        /*
         * préparation du nouveau client, à ajouter, en tant qu'objet Json
         * Les informations le concernant sont renseignées avec des valeurs par défaut,
         * sauf le nom du magasin qui est celui renseigné par l'utilisateur
         */
        toutOk = true;
        JSONArray objetAEnvoyer = new JSONArray();
        JSONObject objet = new JSONObject();
        try {
            objet.put("code_user", Integer.parseInt(codeCompte));
            objetAEnvoyer.put(objet);
        } catch (JSONException e) {
            // l'exception ne doit pas se produire
            toutOk = false;
        }
        if (toutOk) {
            System.out.println(objetAEnvoyer.toString());
            /*
             * préparation du client modifié, en tant qu'objet Json
             * Les informations le concernant sont renseignées avec des valeurs par
             * défaut,
             * sauf le nom du magasin qui est celui renseigné par l'utilisateur
             */
            JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET,
                    URL_LAST_HUMEUR, objetAEnvoyer,
                    // Ecouteur pour la réception de la réponse de la requête
                    new com.android.volley.Response.Listener<JSONArray>() {
                        @Override
                        public void onResponse(JSONArray reponse) {
                            // la zone de résultat est renseignée avec le résultat
//                            aaa.setText(reponse.toString());
//                            StringBuilder resultatFormate = new StringBuilder();
//                            try {
//                                resultatFormate.append(reponse.getString("infos"));
//
//                            } catch (JSONException e) {
//                                throw new RuntimeException(e);
//                            }
//                            aaa.setText(resultatFormate.toString());
                            System.out.println("yesssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss");
                        }
                    },
                    // Ecouteur en cas d'erreur
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
//                            test.setText("erreur : " + error);
                            aaa.setText(error.toString());
                            System.out.println("noooooooooooooooooooooooooooooooooooooooooooooooooooooooooo : " + error.getMessage());
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
//                @Override
//                public byte[] getBody() {
//                    return objet.toString().getBytes();
//                }
//
//                @Override
//                public String getBodyContentType() {
//                    return "application/json";
//                }
            };
            // ajout de la requête dans la file d'attente Volley
            getFileRequete().add(requeteVolley);
        }
    }
    public void recupererHumeurs(String codeCompte, String apikey) {
//        zoneAleatoire.setText(getString(R.string.message_communication) + nombre);
        getFiveHumeurs(codeCompte,apikey);
        System.out.println("code : "+ codeCompte + " " + "Apikey : " + apikey);
    }

    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(getActivity());
        }
        // sinon
        return fileRequete;
    }
}

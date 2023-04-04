package com.example.cymdroid;

import static java.util.Arrays.asList;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import android.widget.Toast;

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

public class FragmentHistorique extends Fragment implements AdapterView.OnItemClickListener, View.OnClickListener {

    private static final String URL_LAST_HUMEUR = "https://cymyellow1.000webhostapp.com/API/fiveLastHumeurs/%s";
    private ListView listeHumeurs;
    private String emojis[];

    private String humeurs[];

    private String dates[];
    private String apiKey;
    private String codeCompte;
    private RequestQueue fileRequete;

    private ArrayList<String> descriptionHumeurs;

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

        listeHumeurs.setOnItemClickListener(this);

        vueDuFragment.findViewById(R.id.btn_refresh).setOnClickListener(this);
//
//        //Création de la ArrayList qui nous permettra de remplir la listView
//        ArrayList<HashMap<String, String>> listItem = new ArrayList<HashMap<String, String>>();
//
//        //On déclare la HashMap qui contiendra les informations pour un item
//        HashMap<String, String> map;
//
//        //Création d'une HashMap pour insérer les informations du premier item de notre listView
//        map = new HashMap<String, String>();
//        //on insère un élément emoji que l'on récupérera dans le textView emoji créé dans le fichier row_item.xml
//        map.put("emoji", "test");
//        //on insère un élément humeur que l'on récupérera dans le textView humeur créé dans le fichier row_item.xml
//        map.put("humeur", "test");
//        //on insère un élément date que l'on récupérera dans le textView date créé dans le fichier row_item.xml
//        map.put("date", "test");
//        //on ajoute cette hashMap dans la arrayList
//        listItem.add(map);
//
//        map = new HashMap<String, String>();
//        map.put("emoji", "test");
//        map.put("humeur", "test");
//        map.put("date", "test");
//        listItem.add(map);
//
//        map = new HashMap<String, String>();
//        map.put("emoji", "test");
//        map.put("humeur", "test");
//        map.put("date", "test");
//        listItem.add(map);
//
//        map = new HashMap<String, String>();
//        map.put("emoji", "test");
//        map.put("humeur", "test");
//        map.put("date", "test");
//        listItem.add(map);
//
//
//        //Création d'un SimpleAdapter qui se chargera de mettre les items présents dans notre list (listItem) dans la vue row_item
//        SimpleAdapter adaptateur = new SimpleAdapter (getActivity(), listItem, R.layout.row_item,
//                new String[] {"emoji", "humeur", "date"}, new int[] {R.id.emoji, R.id.humeur, R.id.date});
//
//        //On attribue à notre listView l'adapter que l'on vient de créer
//        listeHumeurs.setAdapter(adaptateur);

        // TODO tester si la connexion est reussi
//        vueDuFragment.setVisibility(View.INVISIBLE);

//        View vueDuFragment;
//        String codeCompte;
//        String apikey;// nombre à afficher (c'est l'activité principale qui
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
        apiKey = ((MainActivity) getActivity()).getApikey();
        System.out.println("codeCompte : " + codeCompte);
        System.out.println("apikey : " + apiKey);
        /*
         * Dans le cas où aucun nombre aléatoire n'a été généré (ie l'utilisateur n'a pas encore
         * cliqué sur "Générer") , le nombre communiqué par l'activité principale est égal à -1.
         * Si tel est le cas, il ne faut pas l'afficher. +9
         */
        if (codeCompte != null && apiKey != null) {
            vueDuFragment.setVisibility(View.VISIBLE);
            recupererHumeurs(codeCompte,apiKey);
        } else {
            vueDuFragment.setVisibility(View.INVISIBLE);
        }
//        aaa = vueDuFragment.findViewById(R.id.aaa);

        return vueDuFragment;
//        return vueDuFragment;
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
//        System.out.println("a aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: ");
        if (descriptionHumeurs != null) {
            if (descriptionHumeurs.get(i).equals(" ") || descriptionHumeurs.get(i).equals("")) {
                Toast.makeText(getActivity(), R.string.description_erreur, Toast.LENGTH_LONG).show();
            } else {
                Toast.makeText(getActivity(), descriptionHumeurs.get(i), Toast.LENGTH_LONG).show();
            }
//            System.out.println("b :" + descriptionHumeurs.get(i) + "b");

        }
    }

    private void getFiveHumeurs(String codeCompte, String apiKey) {
        String url = String.format(URL_LAST_HUMEUR, codeCompte);
        System.out.println("code = " + codeCompte);
        System.out.println("API = " + apiKey);
            JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET,
                    url, null,
                    // Ecouteur pour la réception de la réponse de la requête
                    new com.android.volley.Response.Listener<JSONArray>() {
                        @Override
                        public void onResponse(JSONArray reponse) {
                            System.out.println(reponse.toString());
                            setZoneResultatAvecObjetJson(reponse);
                        }
                    },
                    // Ecouteur en cas d'erreur
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
//                            test.setText("erreur : " + error);
//                            aaa.setText(error.toString());
                            System.out.println("noooooooooooooooooooooooooooooooooooooooooooooooooooooooooo : " + error.getMessage());
                        }
                    })
                    // on ajoute un header, contenant la clé d'authentification
            {
                @Override
                public Map getHeaders() throws AuthFailureError {
                    HashMap<String, String> headers = new HashMap<>();
                    headers.put("CYMAPIKEY", apiKey);
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
    public void recupererHumeurs(String codeCompteUser, String apikeyUser) {
        if (getView() != null) {
            getView().setVisibility(View.VISIBLE);
        }
        codeCompte = codeCompteUser;
        apiKey = apikeyUser;
//        zoneAleatoire.setText(getString(R.string.message_communication) + nombre);
        getFiveHumeurs(codeCompte,apiKey);
        System.out.println("code : "+ codeCompte + " " + "Apikey : " + apiKey);
    }

    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(getActivity());
        }
        // sinon
        return fileRequete;
    }

    public void setZoneResultatAvecObjetJson(JSONArray reponse) {
//        try {
//            JSONObject humeurs = new JSONObject();
//            humeurs.put("Humeur_Libelle", "ramette papier");
//            ligneCommande1.put("quantite", 10);
//            ligneCommande1.put("prix", 4.5);
//            // Eventuellement pour convertir en chaîne de caractères :
//            String json = ligneCommande.toString() ;
//
//
//
//            JSONObject objetJson = new JSONObject();
            JSONObject ligneHumeurs1 = new JSONObject();
        JSONObject ligneHumeurs2 = new JSONObject();
        JSONObject ligneHumeurs3 = new JSONObject();
        JSONObject ligneHumeurs4 = new JSONObject();
        JSONObject ligneHumeurs5 = new JSONObject();
        ArrayList<HashMap<String, String>> listItem = new ArrayList<HashMap<String, String>>();

//            for (int i = 0; i < reponse.length(); i++) {
//                try {
//                    // on récupère l’objet Json situé en position i dans le tableau
//                    System.out.println(reponse.getJSONObject(i));
//
//                } catch(JSONException erreur) {
////                    Log.i(TAG_LOG, "Problème lors de l'analyse JSON");
//                }
//            }
        try {
            ligneHumeurs1 = reponse.getJSONObject(0);
            ligneHumeurs2 = reponse.getJSONObject(1);
            ligneHumeurs3 = reponse.getJSONObject(2);
            ligneHumeurs4 = reponse.getJSONObject(3);
            ligneHumeurs5 = reponse.getJSONObject(4);

            descriptionHumeurs = new ArrayList<>();
            descriptionHumeurs.add(ligneHumeurs1.getString("Humeur_Description"));
            descriptionHumeurs.add(ligneHumeurs2.getString("Humeur_Description"));
            descriptionHumeurs.add(ligneHumeurs3.getString("Humeur_Description"));
            descriptionHumeurs.add(ligneHumeurs4.getString("Humeur_Description"));
            descriptionHumeurs.add(ligneHumeurs5.getString("Humeur_Description"));
            System.out.println(descriptionHumeurs.toString());

            //On déclare la HashMap qui contiendra les informations pour un item
            HashMap<String, String> map;

            //Création d'une HashMap pour insérer les informations du premier item de notre listView
            map = new HashMap<String, String>();
            //on insère un élément emoji que l'on récupérera dans le textView emoji créé dans le fichier row_item.xml
            map.put("emoji", ligneHumeurs1.getString("Humeur_Emoji"));
            //on insère un élément humeur que l'on récupérera dans le textView humeur créé dans le fichier row_item.xml
            map.put("humeur", ligneHumeurs1.getString("Humeur_Libelle"));
            //on insère un élément date que l'on récupérera dans le textView date créé dans le fichier row_item.xml
            map.put("date", ligneHumeurs1.getString("Humeur_Time"));
            //on ajoute cette hashMap dans la arrayList
            listItem.add(map);

            map = new HashMap<String, String>();
            map.put("emoji", ligneHumeurs2.getString("Humeur_Emoji"));
            map.put("humeur", ligneHumeurs2.getString("Humeur_Libelle"));
            map.put("date", ligneHumeurs2.getString("Humeur_Time"));
            listItem.add(map);

            map = new HashMap<String, String>();
            map.put("emoji", ligneHumeurs3.getString("Humeur_Emoji"));
            map.put("humeur", ligneHumeurs3.getString("Humeur_Libelle"));
            map.put("date", ligneHumeurs3.getString("Humeur_Time"));
            listItem.add(map);

            map = new HashMap<String, String>();
            map.put("emoji", ligneHumeurs4.getString("Humeur_Emoji"));
            map.put("humeur", ligneHumeurs4.getString("Humeur_Libelle"));
            map.put("date", ligneHumeurs4.getString("Humeur_Time"));
            listItem.add(map);

            map = new HashMap<String, String>();
            map.put("emoji", ligneHumeurs5.getString("Humeur_Emoji"));
            map.put("humeur", ligneHumeurs5.getString("Humeur_Libelle"));
            map.put("date", ligneHumeurs5.getString("Humeur_Time"));
            listItem.add(map);

            //Création d'un SimpleAdapter qui se chargera de mettre les items présents dans notre list (listItem) dans la vue row_item
            SimpleAdapter adaptateur = new SimpleAdapter (getActivity(), listItem, R.layout.row_item,
                    new String[] {"emoji", "humeur", "date"}, new int[] {R.id.emoji, R.id.humeur, R.id.date});

            //On attribue à notre listView l'adapter que l'on vient de créer
            listeHumeurs.setAdapter(adaptateur);

        } catch(JSONException erreur) {
        }


//        try {
//            StringBuilder resultatFormate = new StringBuilder();
//            /*
//             * on extrait de l'objet Json reponse : le titre, l'année, les auteurs
//             * On construit la chaine resultatFormate avec des libellés et le chaînes
//             * extraites de l'objet Json
//             */
//            resultatFormate.append(reponse.getString("APIKEY"));
////            System.out.println("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa : " + resultatFormate);
//            // on affiche la chaîne fomratée
//            test.setText(resultatFormate.toString());
//            apiKey = resultatFormate.toString();
////            System.out.println("codeCompteGetApiKey : " + codeCompte);
//            System.out.println("apikeyGetApiKey : " + apiKey);
////            getFiveHumeurs();
//        } catch(JSONException erreur) {
//            test.setText("joqhboqupehbv");
//        }


//            StringBuilder resultatFormate = new StringBuilder();
//            /*
//             * on extrait de l'objet Json reponse : le titre, l'année, les auteurs
//             * On construit la chaine resultatFormate avec des libellés et le chaînes
//             * extraites de l'objet Json
//             */
//            resultatFormate.append(reponse.getString("APIKEY"));
////            System.out.println("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa : " + resultatFormate);
//            // on affiche la chaîne fomratée
//            test.setText(resultatFormate.toString());
//            apiKey = resultatFormate.toString();
////            System.out.println("codeCompteGetApiKey : " + codeCompte);
//            System.out.println("apikeyGetApiKey : " + apiKey);
////            getFiveHumeurs();
//        } catch(JSONException erreur) {
////            test.setText("joqhboqupehbv");
//        }
    }

    @Override
    public void onClick(View view) {
        recupererHumeurs(codeCompte, apiKey);
    }
}

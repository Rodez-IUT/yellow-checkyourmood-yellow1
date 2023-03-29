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
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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

public class MainActivity extends AppCompatActivity implements FragmentAccueil.EcouteurGeneration {

    /** Etiquette pour les messages de log */
//    private static final String TAG_LOG = "ACCES WEB";
    /** URL du Web service, paramétrée par le titre du film recherché (avec %s)
     * Le Web service permet de récupérer une fiche descriptive du film (si elle
     * existe)
     * clé pour utiliser le Web Service = 89f6b9ef
     */
//    private static final String URL_API_KEY = "https://cymyellow1.000webhostapp.com/API/login/%s/%s";
//    private static final String URL_CODE_USER = "https://cymyellow1.000webhostapp.com/API/getCodeUser/%s/%s";
//
//    private static final String URL_LAST_HUMEUR = "https://cymyellow1.000webhostapp.com/API/fiveLastHumeurs";

//    private String nomUtilisateur;
//
//    private String motDePasseUtilisateur;
    private String apiKey;
    private String codeCompte;
//    private RequestQueue fileRequete;
//    private TextView test;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

//        test = findViewById(R.id.test);

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

//        apiKey = "";
//        codeCompte = "";
    }

    @Override
    public void recevoirCodeCompteEtApiKey(String codeCompteRecup, String apiKeyRecup) {
        /* on récupère, via le FragmentManager, un accès au fragment deux.
         * En interne, ce fragment a l'identifiant "f1". Cet identifiant est attributé
         * automatiquement par Android
         */
        codeCompte = codeCompteRecup;
        apiKey = apiKeyRecup;
        System.out.println("codeCompteMain : " + codeCompte);
        System.out.println("apikeyMain : " + apiKey);
        FragmentHistorique fragmentAModifier =
                (FragmentHistorique) getSupportFragmentManager().findFragmentByTag("f1");
        /* Si l'utilisateur n'a pas encore activé l'onglet numéro 2, le fragment f1 n'existe pas
         * encore. Dans ce cas, fragmentAModifier est égal à null. On ne peut donc pas lui
         * envoyer le nombre aléatoire à afficher
         */
//        View fragmentHistorique = getLayoutInflater().inflate(R.layout.fragment_historique, null);
        if (fragmentAModifier != null) {
//            fragmentHistorique.setVisibility(View.VISIBLE);
            fragmentAModifier.recupererHumeurs(codeCompte,apiKey);
        }
    }

    public String getCodeCompte() {
        return codeCompte;
    }

    public String getApikey() {
        return apiKey;
    }
}

package com.example.cymdroid;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager2.widget.ViewPager2;

import android.app.ActionBar;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextClock;

import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

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
    }
}

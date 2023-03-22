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

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;

public class FragmentHistorique extends Fragment implements AdapterView.OnItemClickListener {

    private ListView listeHumeurs;
    private String emojis[];

    private String humeurs[];

    private String dates[];

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

        return vueDuFragment;
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {

    }
}

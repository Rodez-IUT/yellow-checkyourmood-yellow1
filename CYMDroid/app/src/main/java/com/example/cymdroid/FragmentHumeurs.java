package com.example.cymdroid;

import android.app.Activity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextClock;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

public class FragmentHumeurs extends Fragment {

    private TextClock heure;

    private String[] lesHumeurs;
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

        Spinner listeHumeurs = (Spinner) vueDuFragment.findViewById(R.id.spinner);

        lesHumeurs = getResources().getStringArray(R.array.humeurs);

        ArrayAdapter<String> adaptateur = new ArrayAdapter<String>(getActivity(), android.R.layout.simple_spinner_item, lesHumeurs);
        listeHumeurs.setAdapter(adaptateur);
        // on associe un écouteur à la liste déroulante
        listeHumeurs.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        return vueDuFragment;
    }

}

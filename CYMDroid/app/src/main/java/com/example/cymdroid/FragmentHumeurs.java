package com.example.cymdroid;

import android.app.Activity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextClock;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;

public class FragmentHumeurs extends Fragment {

    private TextClock heure;
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

        return vueDuFragment;
    }
}

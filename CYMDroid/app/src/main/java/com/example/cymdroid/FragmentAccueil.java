package com.example.cymdroid;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

public class FragmentAccueil extends Fragment {

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
        return inflater.inflate(R.layout.fragment_accueil, container, false);
    }
}

package com.example.cymdroid;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;

public class AdaptateurPage extends FragmentStateAdapter {

    /** Nombre de fragments gérés par cet adaptateur */
    private static final int NB_FRAGMENT = 3;

    /**
     * Constructeur de base
     * @param activite activité qui contient le ViewPager qui gèrera les fragments
     */
    public AdaptateurPage(FragmentActivity activite) {
        super(activite);
    }
    @Override
    public Fragment createFragment(int position) {
        /*
         * Le ViewPager auquel on associera cet adaptateur devra afficher successivement
         * un fragment de type : FragmentAccueil, puis FragmentHistorique, et enfin FragmentHumeurs
         */
        switch(position) {
            case 0 :
                return FragmentAccueil.newInstance();
            case 1 :
                return FragmentHistorique.newInstance();
            case 2 :
                return FragmentHumeurs.newInstance();
            default :
                return null;
        }
    }

    @Override
    public int getItemCount() {
        return NB_FRAGMENT;
    }
}

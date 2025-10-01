<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Tableau de bord
            'voir_dashboard',

            // Formations
            'voir_formations',
            'creer_formations',
            'modifier_formations',
            'supprimer_formations',

            // Classes
            'voir_classes',
            'creer_classes',
            'modifier_classes',
            'supprimer_classes',

            // Étudiants
            'voir_etudiants',
            'creer_etudiants',
            'modifier_etudiants',
            'supprimer_etudiants',
            'voir_profil_etudiant',

            // Administrateurs
            'voir_admins',
            'creer_admins',
            'modifier_admins',
            'supprimer_admins',
            'voir_profil_admin',

            // Paiement
            'voir_paiements',
            'creer_paiements',
            'paiement_en_ligne',
            'paiement_espece',

            // Liste
            'exporter_liste_classe_etudiant_pdf',
            'exporter_liste_classe_etudiant_excel',

            // Échéance
            'voir_echeances',

            // Historique Paiement
            'voir_recu_paiements',

            // Programme académique
            'gerer_programme',

            // Gestion des rôles
            'voir_roles',
            'creer_roles',
            'modifier_roles',
            'supprimer_roles',

            // Attribution des rôles aux utilisateurs
            'attribuer_roles',

            // Attribution des permissions aux rôles
            'attribuer_permissions',

            // Permissions
            'voir_permissions',
            'creer_permissions',
            'modifier_permissions',
            'supprimer_permissions',

            // Inscriptions
            'voir_inscriptions',
            'creer_inscriptions',
            'modifier_inscriptions',
            'supprimer_inscriptions',
            'voir_mes_inscriptions',
            'valider_inscription',
            'refuser_inscription',
            'voir_detail_inscription',
            'inscriver_etudiant_formation',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use App\Models\Asset;
use PDOException;

class HomeController extends BaseController
{
    public function index(): void
    {
        $modules = [
            [
                'code' => 'M1',
                'titre' => 'Référencement',
                'description' => 'Enregistrement initial des équipements, caractéristiques techniques, valeur d’achat et génération du code d’inventaire.',
                'route' => 'assets',
            ],
            [
                'code' => 'M2',
                'titre' => 'Recherche',
                'description' => 'Recherche multicritère, filtrage par statut ou type, et consultation détaillée de la fiche d’un équipement.',
                'route' => 'assets',
            ],
            [
                'code' => 'M3',
                'titre' => 'Sorties',
                'description' => 'Attribution de matériel aux collaborateurs, date de remise et date prévisionnelle de retour.',
                'route' => 'assignments-create',
            ],
            [
                'code' => 'M4',
                'titre' => 'Restitutions',
                'description' => 'Enregistrement de la restitution du matériel, constat de l’état et remise en disponibilité dans le parc.',
                'route' => 'assignments-return',
            ],
            [
                'code' => 'M5',
                'titre' => 'Maintenance',
                'description' => 'Déclaration de panne, suivi du cycle de vie du ticket d’incident et enregistrement des coûts de réparation.',
                'route' => 'maintenance',
            ],
            [
                'code' => 'M6',
                'titre' => 'Dashboard DSI',
                'description' => 'Tableau de bord décisionnel, volumétrie du parc, valorisation financière globale et disponibilité.',
                'route' => 'dashboard',
            ],
            [
                'code' => 'M7',
                'titre' => 'Alertes de retard',
                'description' => 'Détection automatique des dépassements de date de prêt et génération des alertes de relance.',
                'route' => 'overdue-alerts',
            ],
            [
                'code' => 'M8',
                'titre' => 'Profils & Droits',
                'description' => 'Gestion des habilitations, authentification des utilisateurs et contrôle d’accès selon les rôles DSI.',
                'route' => 'users-roles',
            ],
        ];

        $this->render('home/index', [
            'pageTitle' => 'AssetFlow — Portail DSI',
            'modules' => $modules,
        ]);
    }

    public function assets(): void
    {
        $dbConnected = false;
        $assets = [];

        try {
            if (Database::isConnected()) {
                $dbConnected = true;
                $assetModel = new Asset();
                $assets = $assetModel->findAll();
            }
        } catch (PDOException $e) {
            $dbConnected = false;
        }

        $this->render('assets/index', [
            'pageTitle' => 'Inventaire du Parc Matériel',
            'dbConnected' => $dbConnected,
            'assets' => $assets,
        ]);
    }
    public function form(): void
    {
        $this->render('assets/index', [
            'pageTitle' => 'Formulaire',
        ]);
    }
}

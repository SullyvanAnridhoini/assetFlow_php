<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-brand-600">Référentiel Matériel
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Parc Informatique — Liste des Équipements
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Inventaire complet des actifs informatiques et état d'affectation en temps réel.
            </p>
        </div>

        <a href="index.php?route=form"
            class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
            Rajouter un objet
        </a>

        <a href="index.php?route=home"
            class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
            &larr; Retour au portail
        </a>
    </div>

    <?php if (!$dbConnected): ?>
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-xl text-center space-y-2">
            <h3 class="text-sm font-bold text-amber-900">Base de données non accessible</h3>
            <p class="text-xs text-amber-700">
                Veuillez importer le script <code class="font-bold">sql/00_init_schema.sql</code> pour afficher la liste des
                équipements.
            </p>
        </div>
    <?php elseif (empty($assets)): ?>
        <div class="bg-white border border-slate-200 p-12 rounded-xl text-center space-y-2 text-slate-500">
            <p class="text-sm">Aucun équipement enregistré dans le parc.</p>
        </div>
    <?php else: ?>
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3">Code Inventaire</th>
                            <th class="px-6 py-3">Désignation</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Statut</th>
                            <th class="px-6 py-3">Affectation en cours</th>
                            <th class="px-6 py-3 text-right">Valeur Achat</th>
                            <th class="px-6 py-3">Date Achat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php foreach ($assets as $asset): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-mono font-medium text-slate-900">
                                    <?= e($asset['code_inventaire']) ?>
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-medium">
                                    <?= e($asset['designation']) ?>
                                </td>
                                <td class="px-6 py-4 capitalize text-slate-600 text-xs">
                                    <?= e($asset['type']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statut = $asset['statut'];
                                    $badgeClass = match ($statut) {
                                        'disponible' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'attribue' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rebut' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $badgeClass ?>">
                                        <?= e(ucfirst($statut)) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <?php if (!empty($asset['affecte_nom'])): ?>
                                        <span class="font-medium text-slate-900">
                                            <?= e($asset['affecte_prenom']) ?>             <?= e($asset['affecte_nom']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-400 italic">— Aucun —</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-slate-900 font-semibold">
                                    <?= number_format((float) $asset['valeur_achat'], 2, ',', ' ') ?> &euro;
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                    <?= e($asset['date_achat']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>
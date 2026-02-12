<div class="container admin-logs-container">
    <div class="list-header">
        <h1>Logs d'Activité (NoSQL)</h1>
        <p class="text-dim">Historique des actions administratives stocké dans MongoDB</p>
    </div>

    <?php if (empty($logs)): ?>
        <div class="empty-state">
            <i class="fas fa-history fa-3x"></i>
            <p>Aucun log d'activité pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur ID</th>
                        <th>Action</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <?php
                                // Conversion de l'objet MongoDB UTCDateTime en DateTime PHP
                                $date = $log->created_at->toDateTime();
                                $date->setTimezone(new DateTimeZone('Europe/Paris'));
                                echo $date->format('d/m/Y H:i:s');
                                ?>
                            </td>
                            <td><span class="badge badge-id">
                                    <?= $log->user_id ?>
                                </span></td>
                            <td>
                                <span class="badge badge-action <?= $log->action ?>">
                                    <?= htmlspecialchars($log->action) ?>
                                </span>
                            </td>
                            <td>
                                <pre
                                    class="log-details"><?= json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .admin-logs-container {
        padding: 40px 20px;
    }

    .list-header {
        margin-bottom: 30px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 8px;
        overflow: hidden;
    }

    .admin-table th,
    .admin-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #333;
    }

    .admin-table th {
        background: rgba(111, 66, 193, 0.2);
        color: var(--color-secondary);
        font-family: var(--font-title);
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .badge-id {
        background: #444;
        color: #ddd;
    }

    .badge-action {
        background: var(--color-primary);
        color: white;
        font-size: 0.8rem;
    }

    .badge-action.approbation_personnage {
        background: #2ecc71;
    }

    .badge-action.rejet_personnage {
        background: #e74c3c;
    }

    .badge-action.suppression_accessoire {
        background: #e67e22;
    }

    .badge-action.creation_personnage {
        background: #3498db;
    }

    .badge-action.duplication_personnage {
        background: #9b59b6;
    }

    .log-details {
        font-size: 0.8rem;
        color: var(--color-text-dim);
        background: rgba(0, 0, 0, 0.2);
        padding: 5px;
        border-radius: 4px;
        max-width: 400px;
        overflow-x: auto;
    }
</style>
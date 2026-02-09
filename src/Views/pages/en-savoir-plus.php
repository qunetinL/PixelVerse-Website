<div class="static-page-container container">
    <div class="construction-content">
        <i class="fas fa-tools fa-4x mb-4 text-primary"></i>
        <h1>Page en construction</h1>
        <p class="lead">Cette fonctionnalité sera bientôt disponible ! Revenez plus tard.</p>
        <a href="/" class="btn btn-primary mt-4">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
</div>

<style>
    .static-page-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        min-height: 60vh;
        /* Fallback for older browsers */
    }

    .construction-content {
        max-width: 600px;
        width: 100%;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mb-4 {
        margin-bottom: 25px;
    }

    .mt-4 {
        margin-top: 25px;
    }

    .lead {
        color: var(--color-text-dim);
        font-size: 1.1rem;
        margin-bottom: 30px;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
    }

    @media (max-width: 576px) {
        .static-page-container {
            padding: 40px 15px;
        }

        h1 {
            font-size: 1.6rem;
        }

        .lead {
            font-size: 1rem;
        }

        .fas.fa-4x {
            font-size: 3rem;
        }
    }
</style>
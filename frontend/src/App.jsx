export default function App() {
    return (
        <main className="min-h-screen p-8">
            <section className="glass-card mx-auto max-w-4xl rounded-3xl p-10">
                <p className="font-mono text-sm uppercase tracking-widest text-brand-primary">
                    Mission Control
                </p>

                <h1 className="mt-4 font-display text-5xl font-bold">
                    ETF <span className="rocket-gradient-text">Rocket</span>
                </h1>

                <p className="mt-4 max-w-2xl text-brand-muted">
                    Portfolio telemetry, ETF momentum, and dividend tracking in
                    one command center.
                </p>

                <div className="mt-8 flex gap-4">
                    <button className="rocket-button-primary">
                        Launch Dashboard
                    </button>

                    <button className="rocket-button-secondary">
                        View Health Check
                    </button>
                </div>
            </section>
        </main>
    );
}

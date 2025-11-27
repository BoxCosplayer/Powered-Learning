## Standards and Coding Practices

- Keep the repo layout clear: docs (`README.md`, `psudeocode.md`, `agents.md`) and config (`pyproject.toml`) stay at the root, business logic lives under `src/subject_recommender`, tests under `tests/`, the Laravel app lives in `frontend/` (with Composer/NPM files scoped there), and generated artefacts under `output/`.

- Treat `config.py` as the canonical source for adjustable weights, secret parameters, and session defaults. Other modules should read configuration through helper functions so tests can override values.

- Keep I/O concerns in `io.py`; it is the single access layer for the SQLite database (`data/database.sqlite`). Modules under `preprocessing` and `sessions` should only deal with in-memory data structures passed to them.

- Follow the preprocessing pipeline boundaries: `preprocessing.weighting` handles history iteration and weight application, `preprocessing.aggregation` computes averages/flooring, and `preprocessing.normalisation` normalises scores and selects the next subject. Re-export the orchestration helper via `preprocessing.__init__`.

- Limit session logic to `sessions.generator`. External code should call the function exposed in `sessions.__init__` instead of importing submodules directly.

- Use `utils.py` only for helpers that genuinely span multiple modules; avoid circular dependencies by keeping utilities stateless and pure when possible.

- Mirror the package structure in `tests/` (`test_preprocessing.py`, `test_sessions.py`, etc.) so every public function has unit coverage. Keep exploratory scripts confined to `tests/AlgoTesting.py`.

- Keep exploratory scripts guarded with `if __name__ == "__main__":` and direct any generated artefacts to `output/`, creating the directory if needed so pytest collection stays read-only.

- At the start of each file and function there should be a docstring detailing function/file purpose, inputs & input types and outputs & output types

## Frontend (Laravel)

- The Laravel app resides in `frontend/`; keep Composer and NPM dependencies scoped there and avoid mixing Python tooling or assets into the PHP workspace.

- Use the SQLite connection in `.env` pointed at `../data/database.sqlite` so Laravel and the Python pipeline share one source of truth; migrations in `frontend/database/migrations` must mirror `data/schema.txt` with UUID primary keys, cascade rules, and the same column names.

- Keep route files thin in `frontend/routes`; put HTTP and validation logic in controllers under `frontend/app/Http/Controllers` and models under `frontend/app/Models`, with PHPDoc blocks on files and methods describing purpose, inputs, and outputs.

- Build assets with Vite and Tailwind via `npm run dev` or `npm run build` from `frontend/` and leave generated output in `frontend/public/build` (kept out of version control).

- Add Laravel tests under `frontend/tests` (Feature for endpoints, Unit for narrow helpers) and run them with `composer test` or `php artisan test`, keeping fixtures ephemeral so the shared SQLite database stays deterministic.

## Extra Notes

- All spellings should be in british english

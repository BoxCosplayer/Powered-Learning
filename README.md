# Powered Learning Subject Recommender

Minimal laravel frontend that builds balanced study plans from SQLite assessment history, recency-aware weights, and predicted grades, powered by the subject-recommender engine.

## Implemented features
- Subject weighting pipeline (assessment-type weights, recency decay, predicted grade fallbacks) producing normalised scores.
- Session generator that outputs multi-session revision plans, avoids immediate repeats, and records synthetic history entries.
- Command-line entry point plus data utilities for resetting history and generating entries from predicted grades.

## Frontend status
- Styling is work in progress and currently uses placeholder presentation.

## Planned features

- Autoskip based on timing
- Add timing presets for existing study methods
- Large Knowledge base for different study levels
- Integration with percieved difficulty or comfort levels with subjects
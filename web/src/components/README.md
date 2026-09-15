# Components

Atomic Design, one folder per component (`atoms/Button/Button.tsx` + `index.ts`).
Import the folder (`@/components/atoms/Button`), not a deep file path.

```
atoms/        primitives — no domain words
molecules/    a few atoms with a small contract
organisms/    page sections; may know about websites or health
templates/    slots only (auth, dashboard, detail)
common/       app wiring: layout, providers, document meta
```

Helpers live in `src/lib/`, not here. Routes in `src/app/` compose a template and organisms.

## Placement test

1. Does it mention websites, SSL, plugins, or the current user? It is not an atom.
2. Is it named after a single page? Generalize with props, or keep it in the organism.
3. We do not have CMS `blocks/`. Real UI belongs in molecules or organisms.
4. Reusable in Storybook with dummy props and no API? Atom or molecule.

Do not add a component folder until a task needs that component.

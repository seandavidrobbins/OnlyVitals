import type { ReactNode } from "react";

type EmptyStateProps = {
  title: string;
  description?: string;
  children?: ReactNode;
};

export function EmptyState({ title, description, children }: EmptyStateProps) {
  return (
    <div className="flex flex-col items-start gap-2 rounded-md border border-dashed border-zinc-300 px-4 py-8 dark:border-zinc-700">
      <p className="font-medium text-foreground">{title}</p>
      {description ? (
        <p className="text-sm text-zinc-600 dark:text-zinc-400">{description}</p>
      ) : null}
      {children}
    </div>
  );
}

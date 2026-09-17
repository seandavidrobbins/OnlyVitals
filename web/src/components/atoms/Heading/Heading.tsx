import type { ReactNode } from "react";

type HeadingLevel = "h1" | "h2" | "h3" | "h4" | "h5" | "h6";

type HeadingProps = {
  children: ReactNode;
  as?: HeadingLevel;
};

export function Heading({ children, as: Tag = "h1" }: HeadingProps) {
  return (
    <Tag className="text-2xl font-semibold tracking-tight text-foreground">
      {children}
    </Tag>
  );
}

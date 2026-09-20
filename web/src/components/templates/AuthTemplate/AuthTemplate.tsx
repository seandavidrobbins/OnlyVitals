import type { ReactNode } from "react";

import { Heading } from "@/components/atoms/Heading";

type AuthTemplateProps = {
  title: string;
  children: ReactNode;
};

export function AuthTemplate({ title, children }: AuthTemplateProps) {
  return (
    <main className="mx-auto flex w-full max-w-sm flex-col gap-6 px-6 py-16">
      <Heading>{title}</Heading>
      {children}
    </main>
  );
}

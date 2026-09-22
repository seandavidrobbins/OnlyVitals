import { Heading } from "@/components/atoms/Heading";
import { RequireAuth } from "@/components/common/RequireAuth";
import { SessionStatus } from "@/components/organisms/SessionStatus";

export const metadata = {
  title: "Dashboard · OnlyVitals",
};

export default function DashboardPage() {
  return (
    <RequireAuth>
      <main className="mx-auto flex w-full max-w-lg flex-col gap-4 px-6 py-16">
        <Heading>Dashboard</Heading>
        <SessionStatus />
        <p className="text-sm text-zinc-600 dark:text-zinc-400">
          Website list and health stats land in a later phase.
        </p>
      </main>
    </RequireAuth>
  );
}

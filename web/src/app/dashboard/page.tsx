import { Heading } from "@/components/atoms/Heading";
import { RequireAuth } from "@/components/common/RequireAuth";
import { SessionStatus } from "@/components/organisms/SessionStatus";
import { WebsiteTable } from "@/components/organisms/WebsiteTable";

export const metadata = {
  title: "Dashboard · OnlyVitals",
};

export default function DashboardPage() {
  return (
    <RequireAuth>
      <main className="mx-auto flex w-full max-w-3xl flex-col gap-4 px-6 py-16">
        <Heading>Dashboard</Heading>
        <SessionStatus />
        <WebsiteTable websites={[]} />
      </main>
    </RequireAuth>
  );
}

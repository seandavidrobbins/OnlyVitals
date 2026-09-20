import { Heading } from "@/components/atoms/Heading";
import { Alert } from "@/components/molecules/Alert";
import { SessionStatus } from "@/components/organisms/SessionStatus";
import { getHealth } from "@/lib/api";

export default async function Home() {
  let apiIsReachable = false;

  try {
    await getHealth();
    apiIsReachable = true;
  } catch {
    apiIsReachable = false;
  }

  return (
    <main className="mx-auto flex w-full max-w-lg flex-col gap-4 px-6 py-16">
      <Heading>OnlyVitals</Heading>
      <SessionStatus />
      <Alert tone={apiIsReachable ? "success" : "danger"}>
        {apiIsReachable ? "API ok" : "API unreachable"}
      </Alert>
    </main>
  );
}

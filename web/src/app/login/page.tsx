import { LoginForm } from "@/components/organisms/LoginForm";
import { AuthTemplate } from "@/components/templates/AuthTemplate";

export const metadata = {
  title: "Log in · OnlyVitals",
};

export default function LoginPage() {
  return (
    <AuthTemplate title="Log in">
      <LoginForm />
    </AuthTemplate>
  );
}

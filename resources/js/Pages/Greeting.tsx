import { Head } from "@inertiajs/react";

export default function Greeting() {
  return (
    <>
      <Head title="Greeting" />
      <div className="flex items-center justify-center h-screen bg-gray-100">
        <div className="bg-red-500 p-40 text-white text-center text-2xl">
          Hello World!
        </div>
      </div>
    </>
  );
}

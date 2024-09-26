import { toast, type ToastOptions } from "vue3-toastify";

export function getStatusColor(status: string): string {
  switch (status) {
    case "pending":
      return "warning";
    case "running":
      return "primary";
    case "completed":
      return "success";
    case "failed":
      return "error";
    default:
      return "primary";
  }
}

export function randomColor(): string {
  const colors = [
    "primary",
    "secondary",
    "success",
    "error",
    "warning",
    "info",
  ];

  return colors[Math.floor(Math.random() * colors.length)];
}
export function getRandomColor(): string {
  const colors = [
    "red",
    "pink",
    "purple",
    "deep-purple",
    "indigo",
    "blue",
    "light-blue",
    "cyan",
    "teal",
    "green",
    "light-green",
    "lime",
    "yellow",
    "amber",
    "orange",
    "deep-orange",
    "brown",
    "grey",
    "blue-grey",
  ];

  return colors[Math.floor(Math.random() * colors.length)];
}

export function getInitials(name: string): string {
  return name
    .split(" ")
    .map((word) => word.charAt(0))
    .join("")
    .toUpperCase();
}

export function formatValue(value: number): string {
  const roundedValue = parseFloat(value.toString());
  return roundedValue.toFixed(2);
}

export function getOptions(weight: number, min_weight: number = 1) {
  return Array.from(
    { length: weight - min_weight + 1 },
    (_, i) => i + min_weight
  );
}

export function notify(toastType: ToastOptions["type"], message: string) {
  toast(message, {
    type: toastType,
  });
}

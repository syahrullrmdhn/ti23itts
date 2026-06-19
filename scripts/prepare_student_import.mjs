import fs from "node:fs/promises";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const [inputPath, outputPath] = process.argv.slice(2);

if (!inputPath || !outputPath) {
  throw new Error("Usage: prepare_student_import.mjs <input.xlsx> <output.json>");
}

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(inputPath));
const sheet = workbook.worksheets.items.find((item) => item.name === "TI");

if (!sheet) {
  throw new Error('Sheet "TI" was not found.');
}

const excelDate = (serial) => {
  if (!serial) return null;
  const date = new Date(Date.UTC(1899, 11, 30) + Number(serial) * 86400000);
  return date.toISOString().slice(0, 10);
};

let classType = null;
let entryType = "Reguler";
const allStudents = [];

for (const row of sheet.getRange("A1:J200").values) {
  const [number, status, nim, name, email, gender, birthPlace, birthDate, religion, phone] = row;
  const firstCell = String(number ?? "").trim();
  const statusCell = String(status ?? "").trim();

  if (firstCell.includes("KELAS REGULER")) {
    classType = "Pagi";
    entryType = "Reguler";
    continue;
  }

  if (firstCell.includes("KELAS KARYAWAN")) {
    classType = "Sore";
    entryType = "Reguler";
    continue;
  }

  if (statusCell.includes("Pindahan Murni")) {
    entryType = "Pindahan Murni";
    continue;
  }

  if (statusCell.includes("Ekstensi")) {
    entryType = "Ekstensi";
    continue;
  }

  if (!/^\d{10,}$/.test(String(nim ?? "").trim()) || !name) {
    continue;
  }

  allStudents.push({
    nim: String(nim).trim(),
    name: String(name).trim().replace(/\s+/g, " "),
    email: email ? String(email).trim().toLowerCase() : null,
    gender: gender ? String(gender).trim() : null,
    birth_place: birthPlace ? String(birthPlace).trim() : null,
    birth_date: excelDate(birthDate),
    religion: religion ? String(religion).trim() : null,
    phone: phone ? String(phone).trim() : null,
    class_type: classType,
    entry_type: entryType,
    source_status: statusCell.toUpperCase(),
    role: "Mahasiswa TI '23",
    status: "Aktif",
    photo: null,
    aib_photo: null,
    fun_fact: null,
  });
}

const activeStudents = allStudents
  .filter((student) => student.source_status === "AKTIF")
  .map(({ source_status, ...student }) => student);

const payload = {
  generated_at: new Date().toISOString(),
  source: inputPath.split("/").pop(),
  total_rows: allStudents.length,
  excluded_non_active: allStudents.length - activeStudents.length,
  students: activeStudents,
};

await fs.writeFile(outputPath, JSON.stringify(payload, null, 2), { mode: 0o600 });
console.log(JSON.stringify({
  totalRows: payload.total_rows,
  activeStudents: payload.students.length,
  excludedNonActive: payload.excluded_non_active,
}, null, 2));

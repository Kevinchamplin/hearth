// Hearth desktop entry point. All the real work lives in the library (lib.rs) so the
// same code path can target mobile later. Hide the console window on Windows release.
#![cfg_attr(not(debug_assertions), windows_subsystem = "windows")]

fn main() {
    hearth_lib::run()
}

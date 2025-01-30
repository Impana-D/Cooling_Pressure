import pandas as pd
import numpy as np
import os
import sys
import json

# Directory to look for the Excel file
excel_directory = "/opt/lampp/htdocs/cooling_pressure2/uploads/null"

def get_latest_excel_file(directory):
    """Fetch the latest Excel file from the specified directory."""
    try:
        # List all files in the directory
        files = [f for f in os.listdir(directory) if f.endswith(('.xlsx', '.xls'))]
        if not files:
            raise FileNotFoundError("No Excel files found in the directory.")
        
        # Get the latest file based on modification time
        files = sorted(files, key=lambda f: os.path.getmtime(os.path.join(directory, f)), reverse=True)
        latest_file = os.path.join(directory, files[0])
        return latest_file
    except Exception as e:
        print(f"Error fetching Excel file: {e}")
        sys.exit()

# Fetch the latest Excel file dynamically
excel_file = get_latest_excel_file(excel_directory)

try:
    raw_data = pd.read_excel(excel_file, header=None)
    headers = raw_data.iloc[0].fillna('') + ' ' + raw_data.iloc[1].fillna('')
    headers = headers.str.strip().str.replace('\n', ' ').str.replace('\r', ' ')
    data = pd.read_excel(excel_file, skiprows=2, header=None)
    data.columns = headers
except Exception as e:
    print(f"Error reading the file: {e}")
    sys.exit()

data.rename(columns={
    "Pressure (kPa)": "Pressure",
    "Enthalpy KJ/kg Sat.liquid Hf": "Sat.liquid Hf",
    "Enthalpy KJ/kg Evap Hfg": "Evap Hfg",
    "Enthalpy KJ/kg Sat.vapour Hg": "Sat.vapour Hg",
    "Entropy KJ/Kg.K Sat.liquid Sf": "Sat.liquid Sf",
    "Entropy KJ/Kg.K Sat.vapour Sg": "Sat.vapour Sg"
}, inplace=True)

required_columns = ["Pressure", "Sat.liquid Hf", "Evap Hfg", "Sat.vapour Hg", "Sat.liquid Sf", "Sat.vapour Sg"]
data = data[required_columns]

missing_columns = [col for col in required_columns if col not in data.columns]
if missing_columns:
    print(f"Missing columns in the dataset: {missing_columns}")
    sys.exit("Error: Required columns not found in the dataset.")

def convert_to_kpa(pressure, unit):
    if unit.lower() == "kpa":
        return pressure
    elif unit.lower() == "mpa":
        return pressure * 1000
    elif unit.lower() == "bar":
        return pressure * 100
    else:
        raise ValueError("Unsupported pressure unit. Use 'kPa', 'MPa', or 'bar'.")

def interpolate_properties(pressure_kpa, data):
    data = data.sort_values(by="Pressure").reset_index(drop=True)
    if pressure_kpa < data["Pressure"].min() or pressure_kpa > data["Pressure"].max():
        raise ValueError("Pressure is outside the data range.")
    
    lower_row = data[data["Pressure"] <= pressure_kpa].iloc[-1]
    upper_row = data[data["Pressure"] >= pressure_kpa].iloc[0]
    
    properties = {}
    for col in ["Sat.liquid Hf", "Evap Hfg", "Sat.vapour Hg", "Sat.liquid Sf", "Sat.vapour Sg"]:
        properties[col] = np.interp(
            pressure_kpa,
            [lower_row["Pressure"], upper_row["Pressure"]],
            [lower_row[col], upper_row[col]],
        )
    return properties

def main():
    if len(sys.argv) != 5:
        print("Usage: python3 process.py <P_high> <P_low> <p_unit> <mass_flow_rate>")
        return

    P_high = float(sys.argv[1])
    P_low = float(sys.argv[2])
    p_unit = int(sys.argv[3])
    mass_flow_rate = float(sys.argv[4])
    
    input_unit = {1: 'kPa', 2: 'MPa'}.get(p_unit, 'bar')
    pressure_values_in_kpa = [convert_to_kpa(p, input_unit) for p in [P_high, P_low]]

    interpolated_results = {}
    try:
        for pressure_in_kpa in pressure_values_in_kpa:
            interpolated_results[pressure_in_kpa] = interpolate_properties(pressure_in_kpa, data)
    except ValueError as e:
        print(e)
        sys.exit()

    h1 = interpolated_results[pressure_values_in_kpa[1]]["Sat.vapour Hg"]
    s1 = interpolated_results[pressure_values_in_kpa[1]]["Sat.vapour Sg"]
    h2 = interpolated_results[pressure_values_in_kpa[0]]["Sat.vapour Hg"]
    h3 = interpolated_results[pressure_values_in_kpa[0]]["Sat.liquid Hf"]
    h4 = h3

    Q_in = mass_flow_rate * (h1 - h4)
    W_comp = mass_flow_rate * (h2 - h1)
    Q_out = mass_flow_rate * (h2 - h3)
    COP = Q_in / W_comp

    output = {
        "h1": h1,
        "s1": s1,
        "h2": h2,
        "h3": h3,
        "h4": h4,
        "Q_in": Q_in,
        "W_comp": W_comp,
        "Q_out": Q_out,
        "COP": COP
    }
    print(json.dumps(output))

if __name__ == "__main__":
    main()


<div align="center">

# TrainGPS Safety Intelligence

**Location-aware railway risk analysis with embedded GPS and driver-attention monitoring.**

<img src="https://img.shields.io/badge/Completed_research--oriented_project-4F86FF?style=flat-square&labelColor=0B1224" alt="Completed research-oriented project" /> <img src="https://img.shields.io/badge/Public_repository-4F86FF?style=flat-square&labelColor=0B1224" alt="Public repository" />

[Portfolio](https://kavindudamsith.tech/) &nbsp;|&nbsp; [LinkedIn](https://www.linkedin.com/in/kavindu-damsith-86696722a/) &nbsp;|&nbsp; [Email](mailto:kavindudamsith65@gmail.com)

</div>

---

## Overview

TrainGPS explores a warning system for reducing railway accidents. It combines ESP32 GPS capture, railway and station datasets, trained location and crack-risk models, a Django service, a PHP interface, and real-time driver drowsiness detection.

## My contribution

The project combines embedded, web, data, and computer-vision work into one safety prototype.

## What it does

| Area | Details |
| --- | --- |
| **Live location** | Arduino code reads and publishes GPS data from the train. |
| **Risk estimation** | Historical railway and crack datasets support polynomial prediction experiments. |
| **Driver attention** | A drowsiness-detection module monitors the driver. |
| **Operational interface** | PHP and Django applications expose location and risk information. |
| **Geospatial data** | Railway shapefiles, station data, train paths, and notebooks support model development. |

## Repository map

| Path | Purpose |
| --- | --- |
| `esp32/codeGps/` | Embedded GPS capture and publishing code. |
| `trainDamageReduction/mainPro/mlModels/` | Location and crack models, scalers, notebooks, and datasets. |
| `trainDamageReduction/mainPro/project/` | Driver drowsiness detection. |
| `trainDamageReduction/` | Django project and application. |
| `app_model/` | PHP dashboard, administration, styling, and ESP data endpoint. |

## Technology

- **Python**
- **Django**
- **PHP**
- **ESP32**
- **Jupyter**
- **Geospatial Data**
- **Machine Learning**

### Configuration notes

The repository is a multi-component archive rather than a one-command application. Configure hardware endpoints, database values, Python dependencies, and model paths separately.

## Status

Completed research-oriented project.

## Links

- [Portfolio project index](https://kavindudamsith.tech/#work)

---

Questions about this repository? [Email me](mailto:kavindudamsith65@gmail.com) or connect on [LinkedIn](https://www.linkedin.com/in/kavindu-damsith-86696722a/).

# Technical component map: `index_archivos/libs/datatables/datatables.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N4b24e7ddcdf3["index_archivos/libs/datatables/datatables.js"]
  N8a9ab1320431["SCHEDULED_TASK: index_archivos/libs/datatables/datatables.js"]
  Nb8d83dea9dec["-1"]
  N1e9863485105["0"]
  N762de2458a02["BUSY_STATE"]
  N4b0723977b9d["COMMENT_STATE"]
  Nc7937f3f0d08["EXTRA_STATE"]
  N11f7a8a025de["FINISH_STATE"]
  Nf87b15fe060c["GenStateCompleted"]
  Ned99dce1eaaa["GenStateExecuting"]
  N7d45aec04387["GenStateSuspendedStart"]
  Nd9046eb325da["HCRC_STATE"]
  Ne5f9218a48a2["INITIAL_STATE"]
  N6514530e6367["NAME_STATE"]
  N276ebcb177fb["_STATE_TABLE"]
  N88659245f2ff["arguments"]
  N4409a261df4d["context"]
  N954ac11a6034["dt"]
  N830aa891bc74["new"]
  Neb6d4b2c5184["nextState"]
  N3c823c6219d1["null"]
  N3f1fb053d764["s"]
  N98c3d7dfcb34["self"]
  N21aa0a5a798e["src"]
  N917ee7df9460["state"]
  N80465fc86cfd["stream"]
  N528a0b8be85c["strm"]
  N0d43eeea99a9["this"]
  Nbfa1b065b7af["zlib_deflate"]
  Nc8a925ef8f49["zlib_inflate"]
  N4b24e7ddcdf3 -- "sets_state" --> Nb8d83dea9dec
  N4b24e7ddcdf3 -- "sets_state" --> N1e9863485105
  N4b24e7ddcdf3 -- "sets_state" --> N1e9863485105
  N4b24e7ddcdf3 -- "sets_state" --> N1e9863485105
  N4b24e7ddcdf3 -- "sets_state" --> N1e9863485105
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N762de2458a02
  N4b24e7ddcdf3 -- "sets_state" --> N4b0723977b9d
  N4b24e7ddcdf3 -- "sets_state" --> N4b0723977b9d
  N4b24e7ddcdf3 -- "sets_state" --> N4b0723977b9d
  N4b24e7ddcdf3 -- "sets_state" --> N4b0723977b9d
  N4b24e7ddcdf3 -- "sets_state" --> Nc7937f3f0d08
  N4b24e7ddcdf3 -- "sets_state" --> Nc7937f3f0d08
  N4b24e7ddcdf3 -- "sets_state" --> N11f7a8a025de
  N4b24e7ddcdf3 -- "sets_state" --> N11f7a8a025de
  N4b24e7ddcdf3 -- "sets_state" --> Nf87b15fe060c
  N4b24e7ddcdf3 -- "sets_state" --> Nf87b15fe060c
  N4b24e7ddcdf3 -- "sets_state" --> Ned99dce1eaaa
  N4b24e7ddcdf3 -- "sets_state" --> N7d45aec04387
  N4b24e7ddcdf3 -- "sets_state" --> Nd9046eb325da
  N4b24e7ddcdf3 -- "sets_state" --> Nd9046eb325da
  N4b24e7ddcdf3 -- "sets_state" --> Nd9046eb325da
  N4b24e7ddcdf3 -- "sets_state" --> Nd9046eb325da
  N4b24e7ddcdf3 -- "sets_state" --> Ne5f9218a48a2
  N4b24e7ddcdf3 -- "sets_state" --> Ne5f9218a48a2
  N4b24e7ddcdf3 -- "sets_state" --> N6514530e6367
  N4b24e7ddcdf3 -- "sets_state" --> N6514530e6367
  N4b24e7ddcdf3 -- "sets_state" --> N6514530e6367
  N4b24e7ddcdf3 -- "sets_state" --> N6514530e6367
  N4b24e7ddcdf3 -- "sets_state" --> N276ebcb177fb
  N4b24e7ddcdf3 -- "sets_state" --> N276ebcb177fb
  N4b24e7ddcdf3 -- "sets_state" --> N88659245f2ff
  N4b24e7ddcdf3 -- "sets_state" --> N4409a261df4d
  N4b24e7ddcdf3 -- "sets_state" --> N954ac11a6034
  N4b24e7ddcdf3 -- "sets_state" --> N830aa891bc74
  N4b24e7ddcdf3 -- "sets_state" --> N830aa891bc74
  N4b24e7ddcdf3 -- "sets_state" --> Neb6d4b2c5184
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3c823c6219d1
  N4b24e7ddcdf3 -- "sets_state" --> N3f1fb053d764
  N4b24e7ddcdf3 -- "sets_state" --> N3f1fb053d764
  N4b24e7ddcdf3 -- "sets_state" --> N98c3d7dfcb34
  N4b24e7ddcdf3 -- "sets_state" --> N98c3d7dfcb34
  N4b24e7ddcdf3 -- "sets_state" --> N21aa0a5a798e
  N4b24e7ddcdf3 -- "sets_state" --> N917ee7df9460
  N4b24e7ddcdf3 -- "sets_state" --> N917ee7df9460
  N4b24e7ddcdf3 -- "sets_state" --> N80465fc86cfd
  N4b24e7ddcdf3 -- "sets_state" --> N80465fc86cfd
  N4b24e7ddcdf3 -- "sets_state" --> N80465fc86cfd
  N4b24e7ddcdf3 -- "sets_state" --> N80465fc86cfd
  N4b24e7ddcdf3 -- "sets_state" --> N80465fc86cfd
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N528a0b8be85c
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> N0d43eeea99a9
  N4b24e7ddcdf3 -- "sets_state" --> Nbfa1b065b7af
  N4b24e7ddcdf3 -- "sets_state" --> Nbfa1b065b7af
  N4b24e7ddcdf3 -- "sets_state" --> Nbfa1b065b7af
  N4b24e7ddcdf3 -- "sets_state" --> Nc8a925ef8f49
  N4b24e7ddcdf3 -- "sets_state" --> Nc8a925ef8f49
  N4b24e7ddcdf3 -- "sets_state" --> Nc8a925ef8f49
  N8a9ab1320431 -- "handled_by" --> N4b24e7ddcdf3
```

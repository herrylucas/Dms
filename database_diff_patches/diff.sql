alter table trucks
    drop column troadlicense;

alter table trailers
    drop column trroadlicense;

alter table trucks
	add tcomesa date;

alter table trucks
	add troadpermit date;

alter table trucks
	add tcarbontax date;


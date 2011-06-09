#include <stdio.h>

/*
  This file generates the sql command to populate the 'fluxes' table.
  Change 'total' if you want more row (why not trying 100000 or more >:D...)
*/

int main(int argc, char* argv) {
  int i;
  int total = 10;
  printf("INSERT INTO fluxes\n(flux_id,name,money_waiting)\nVALUES\n");
  for (i=0;i<total;i++) {
    printf("(%d,'%d',%d)",i+1,i,i);
    if (i!=total-1) printf(",\n");
    else printf("\n");
  }
  printf(";\n");
  return 0;
}
